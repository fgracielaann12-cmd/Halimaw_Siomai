<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    // 🧩 Login Page
    public function login()
    {
        return view('auth/login');
    }

    // 🔐 Handle Login
    public function authenticate()
    {
        $session = session();
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->where('username', $username)->first();

        if ($user) {
            // ✅ Verify password
            if (password_verify($password, $user['password'])) {
                // ✅ Set session data
                $session->set([
                    'logged_in' => true,
                    'user_id'   => $user['id'],
                    'username'  => $user['username'],
                    'email'     => $user['email'],
              //      'role'      => $user['role'],
                ]);

                // ✅ Redirect based on role
          //      if ($user['role'] === 'admin') {
       //             return redirect()->to('/dashboard');
        //        } else {
        //            return redirect()->to('/items');
        //        }
       //     } else {
       //         return redirect()->back()->with('error', '❌ Incorrect password. Please try again.');
            }
     //   } else {
     //       return redirect()->back()->with('error', '⚠️ User not found. Please register first.');
        }
    }

    // 🧾 Registration Page
    public function register()
    {
        return view('auth/register');
    }

    // 🧾 Save Registration
    public function save()
    {
        helper(['form']);
        $userModel = new UserModel();

        $rules = [
            'username'          => 'required|min_length[3]|is_unique[users.username]',
            'email'             => 'required|valid_email|is_unique[users.email]',
            'password'          => 'required|min_length[6]',
            'confirm_password'  => 'matches[password]',
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            return redirect()->back()->withInput()->with('error', implode('<br>', $errors));
        }

        $userModel->save([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
     //       'role'     => 'user', // default role
        ]);

        return redirect()->to('/')->with('success', '✅ Account created successfully! You can now log in.');
    }

    // 🚪 Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', '👋 Logged out successfully.');
    }
}
