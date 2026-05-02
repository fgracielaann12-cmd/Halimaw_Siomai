<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    public function login()
    {
        return view('auth/login');
    }

    public function authenticate()
    {
        $session = session();
        $loginInput = $this->request->getPost('login'); // username or email
        $password = $this->request->getPost('password');

        $user = $this->userModel
                     ->where('username', $loginInput)
                     ->orWhere('email', $loginInput)
                     ->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', '⚠️ User not found.');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', '❌ Incorrect password.');
        }

        $session->set([
            'isLoggedIn' => true,
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ]);

        return $user['role'] === 'admin'
            ? redirect()->to('/admin/dashboard')
            : redirect()->to('/user/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', '👋 Logged out successfully.');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function save()
    {
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => 'user'
        ]);

        return redirect()->to('/login')->with('success', '✅ Account created successfully! You can now log in.');
    }
}
