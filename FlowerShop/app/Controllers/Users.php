<?php

namespace App\Controllers;

class Users extends BaseController
{
    public function index()
    {
        $data['users'] = [
            ['username' => 'admin_user', 'full_name' => 'Administrator', 'role' => 'Admin'],
            ['username' => 'cashier_jane', 'full_name' => 'Jane Doe', 'role' => 'Cashier'],
            ['username' => 'cashier_john', 'full_name' => 'John Smith', 'role' => 'Cashier'],
            ['username' => 'manager_sarah', 'full_name' => 'Sarah Connor', 'role' => 'Manager'],
            ['username' => 'supervisor_mike', 'full_name' => 'Michael Ross', 'role' => 'Supervisor'],
        ];

        return view('users', $data);
    }
}