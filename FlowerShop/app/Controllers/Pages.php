<?php

namespace App\Controllers;

use App\Models\ProductModel;
use Config\Database;

class Pages extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $products     = $productModel->orderBy('id', 'DESC')->findAll();

        foreach ($products as &$product) {
            $product['image_url'] = !empty($product['image'])
                ? base_url('uploads/products/' . $product['image'])
                : base_url('uploads/products/default-product.png');
        }
        unset($product);

        return view('home', [
            'products' => $products,
        ]);
    }

    public function about()
    {
        $db    = Database::connect();
        $owner = null;
        $staff = [];

        if ($db->tableExists('users')) {
            $fields = $db->getFieldNames('users');

            $nameCol = null;
            foreach (['full_name', 'fullname', 'name', 'username'] as $candidate) {
                if (in_array($candidate, $fields, true)) {
                    $nameCol = $candidate;
                    break;
                }
            }

            $avatarCol = null;
            foreach (['avatar', 'profile_image', 'image', 'photo', 'picture'] as $candidate) {
                if (in_array($candidate, $fields, true)) {
                    $avatarCol = $candidate;
                    break;
                }
            }

            $hasRoleCol = in_array('role', $fields, true);

            $selectCols = ['id'];
            if ($nameCol) {
                $selectCols[] = $nameCol;
            }
            if ($avatarCol) {
                $selectCols[] = $avatarCol;
            }
            if ($hasRoleCol) {
                $selectCols[] = 'role';
            }

            $rows = $db->table('users')
                ->select(implode(', ', $selectCols))
                ->orderBy('id', 'ASC')
                ->get()
                ->getResultArray();

            foreach ($rows as $row) {
                $fullName = trim((string) ($nameCol ? ($row[$nameCol] ?? '') : ''));
                if ($fullName === '') {
                    $fullName = 'Staff #' . ($row['id'] ?? '');
                }

                $rawAvatar = trim((string) ($avatarCol ? ($row[$avatarCol] ?? '') : ''));
                $avatarUrl = $this->resolveAvatarUrl($rawAvatar);

                $member = [
                    'full_name'  => $fullName,
                    'avatar_url' => $avatarUrl,
                    'initials'   => $this->buildInitials($fullName),
                ];

                $role = $hasRoleCol ? strtolower(trim((string) ($row['role'] ?? ''))) : '';

                if ($owner === null && $role === 'owner') {
                    $owner = $member;
                    continue;
                }

                $staff[] = $member;
            }
        }

        return view('about', [
            'owner' => $owner,
            'staff' => $staff,
        ]);
    }

    private function resolveAvatarUrl(string $filename): ?string
    {
        if ($filename === '') {
            return null;
        }

        if (preg_match('#^https?://#i', $filename)) {
            return $filename;
        }

        $clean = ltrim($filename, '/');
        $candidates = [
            'uploads/avatars/' . $clean,
            'uploads/users/' . $clean,
            'uploads/' . $clean,
            $clean,
        ];

        foreach ($candidates as $relPath) {
            if (is_file(FCPATH . $relPath)) {
                return base_url($relPath);
            }
        }

        return null;
    }

    private function buildInitials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $initials = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            if ($part !== '') {
                $initials .= mb_strtoupper(mb_substr($part, 0, 1));
            }
        }

        return $initials !== '' ? $initials : 'ST';
    }
}