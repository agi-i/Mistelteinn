<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Prepare a display-ready avatar URL for views
    private function prepareAvatar(?string $filename): string
    {
        if (empty($filename)) {
            return base_url('uploads/avatars/default-avatar.png');
        }

        $fullPath = FCPATH . 'uploads/avatars/' . $filename;
        if (!is_file($fullPath)) {
            return base_url('uploads/avatars/default-avatar.png');
        }

        return base_url('uploads/avatars/' . $filename);
    }

    // Handle avatar file upload into public/uploads/avatars/
    private function handleAvatarUpload(): ?string
    {
        $file = $this->request->getFile('avatar');

        // Return null if no avatar file was chosen
        if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads/avatars';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);

            return $newName;
        }

        return null;
    }

    // LIST all staff users with display-ready avatar URLs
    public function index()
    {
        $users = $this->userModel->orderBy('id', 'DESC')->findAll();

        foreach ($users as &$user) {
            // Never pass password hashes to views unnecessarily
            unset($user['password']);
            $user['avatar_url'] = $this->prepareAvatar($user['avatar'] ?? null);
        }
        unset($user);

        return view('users/index', [
            'users' => $users,
        ]);
    }

    // SHOW create staff form
    public function new()
    {
        return view('users/form', [
            'user' => null,
        ]);
    }

    // STORE new staff user (password hashed automatically by UserModel)
    public function create()
    {
        $rules = [
            'name'     => 'required|min_length[2]|max_length[150]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,cashier,staff]',
            'avatar'   => 'permit_empty|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]|max_size[avatar,2048]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $avatarName = $this->handleAvatarUpload();

        $this->userModel->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('role'),
            'avatar'   => $avatarName,
        ]);

        return redirect()->to('/users')->with('message', 'Staff member created successfully.');
    }

    // SHOW edit staff form
    public function edit($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/users')->with('error', 'Staff member not found.');
        }

        unset($user['password']);
        $user['avatar_url'] = $this->prepareAvatar($user['avatar'] ?? null);

        return view('users/form', [
            'user' => $user,
        ]);
    }

    // UPDATE existing staff user
    public function update($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/users')->with('error', 'Staff member not found.');
        }

        // Password is optional when editing; if entered, require at least 6 chars
        $passwordRule = $this->request->getPost('password') !== ''
            ? 'min_length[6]'
            : 'permit_empty';

        $rules = [
            'name'     => 'required|min_length[2]|max_length[150]',
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]",
            'password' => $passwordRule,
            'role'     => 'required|in_list[admin,cashier,staff]',
            'avatar'   => 'permit_empty|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]|max_size[avatar,2048]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $avatarName = $user['avatar'] ?? null;
        $newAvatar  = $this->handleAvatarUpload();

        if ($newAvatar !== null) {
            if (!empty($avatarName) && is_file(FCPATH . 'uploads/avatars/' . $avatarName)) {
                @unlink(FCPATH . 'uploads/avatars/' . $avatarName);
            }
            $avatarName = $newAvatar;
        }

        $data = [
            'name'   => $this->request->getPost('name'),
            'email'  => $this->request->getPost('email'),
            'role'   => $this->request->getPost('role'),
            'avatar' => $avatarName,
        ];

        // Only include password if user typed a new one
        $newPassword = (string) $this->request->getPost('password');
        if ($newPassword !== '') {
            $data['password'] = $newPassword;
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/users')->with('message', 'Staff member updated successfully.');
    }

    // DELETE staff user and remove avatar file
    public function delete($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/users')->with('error', 'Staff member not found.');
        }

        if (!empty($user['avatar'])) {
            $avatarPath = FCPATH . 'uploads/avatars/' . $user['avatar'];
            if (is_file($avatarPath)) {
                @unlink($avatarPath);
            }
        }

        $this->userModel->delete($id);

        return redirect()->to('/users')->with('message', 'Staff member deleted successfully.');
    }
}