<?php

namespace App\Controllers;

use App\Models\UserModel;

class AdminAuth extends BaseController
{
    public function login()
    {
        return view('auth/adminlogin');
    }

    public function authenticate()
    {
        $session = session();
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->where('username', $username)->first();

        if ($user) {
            if ($user['role'] === 'admin' && password_verify($password, $user['password'])) {
                $session->set([
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'isLoggedIn' => true
                ]);
                return redirect()->to('/admin/dashboard');
            } else {
                $session->setFlashdata('error', 'Invalid admin credentials.');
                return redirect()->to('/admin/login');
            }
        } else {
            $session->setFlashdata('error', 'Admin not found.');
            return redirect()->to('/admin/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login');
    }
}
