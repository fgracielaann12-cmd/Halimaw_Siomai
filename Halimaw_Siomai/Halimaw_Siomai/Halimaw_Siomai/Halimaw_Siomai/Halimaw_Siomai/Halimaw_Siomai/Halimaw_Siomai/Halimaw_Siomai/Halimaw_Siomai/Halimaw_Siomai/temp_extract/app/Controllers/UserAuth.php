<?php

namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\Controller;

class UserAuth extends Controller
{
    public function login()
    {
        return view('auth/userlogin'); // ✅ Make sure this file exists: app/Views/auth/user_login.php
    }

    public function authenticate()
    {
        $session = session();
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)
                      ->where('role', 'user')
                      ->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'logged_in' => true,
                'user_id'   => $user['id'],
                'role'      => 'user',
                'username'  => $user['username']
            ]);
            return redirect()->to('/user/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid user credentials.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
