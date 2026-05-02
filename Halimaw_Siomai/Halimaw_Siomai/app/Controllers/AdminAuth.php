<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AdminAuth extends Controller
{
    // 🧩 Admin Login Page
    public function login()
    {
        // If already logged in as admin → redirect directly
        if (session()->get('logged_in') && session()->get('role') === 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        return view('auth/adminlogin');
    }

    // 🔐 Handle Admin Authentication
    public function authenticate()
    {
        $session = session();
        $userModel = new \App\Models\UserModel();

        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));

        $user = $userModel->where('username', $username)->first();
        if (!$user || !password_verify($password, $user['password']) || $user['role'] !== 'admin') {
            $session->setFlashdata('error', 'Invalid credentials or not admin');
            return redirect()->back()->withInput();
        }

        // set session only on success
        $session->set([
            'logged_in' => true,
            'role'      => 'admin',
            'username'  => $user['username'],
        ]);

        return redirect()->to('/admin/dashboard');
    }
}
