<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Register extends Controller
{
    public function index()
    {
        // Show the registration page
        return view('auth/register');
    }

    public function save()
    {
        helper(['form']);
        $userModel = new UserModel();

        // Validation rules
        $rules = [
            'username'         => 'required|min_length[3]|is_unique[users.username]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'matches[password]',
        ];

        if (!$this->validate($rules)) {
            // Return validation errors
            $errors = $this->validator->getErrors();
            return redirect()->back()->withInput()->with('error', implode('<br>', $errors));
        }

        // Save new user
        $userModel->save([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user', // default role
        ]);

        return redirect()->to('/login')->with('success', '✅ Account created successfully! You can now log in.');
    }
}
