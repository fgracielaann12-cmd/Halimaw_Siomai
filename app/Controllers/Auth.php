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
        if (session()->get('isLoggedIn') || session()->get('logged_in')) {
            return session()->get('role') === 'admin' 
                ? redirect()->to('/admin/dashboard') 
                : redirect()->to('/user/dashboard');
        }
        return view('auth/login');
    }

    public function authenticate()
    {
        $session = session();

        // Check if locked out first
        if ($session->get('lockout_time')) {
            if (time() < $session->get('lockout_time')) {
                return redirect()->back(); // View will handle showing the persistent timeout message
            } else {
                $session->remove(['login_attempts', 'lockout_time']);
            }
        }

        $loginInput = $this->request->getPost('login'); // username or email
        $password = $this->request->getPost('password');

        $user = $this->userModel
                     ->where('username', $loginInput)
                     ->orWhere('email', $loginInput)
                     ->first();

        // If user not found OR incorrect password, count as failed attempt
        if (!$user || !password_verify($password, $user['password'])) {
            // Exclude admin accounts from being locked out
            if ($user && $user['role'] === 'admin') {
                return redirect()->back()->withInput()->with('error', '⚠️ Invalid Username and Password!');
            }

            $attempts = $session->get('login_attempts') ?? 0;
            $attempts++;
            $session->set('login_attempts', $attempts);

            if ($attempts >= 3) {
                $session->set('lockout_time', time() + (15 * 60)); // 15 mins
                return redirect()->back();
            }

            return redirect()->back()->withInput()->with('error', '⚠️ Invalid Username and Password!');
        }

        // On successful login, reset attempts
        $session->remove(['login_attempts', 'lockout_time']);

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
        $session = session();
        $session->remove(['isLoggedIn', 'logged_in', 'user_id', 'username', 'email', 'role']);
        $session->destroy();
        $_SESSION = [];
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
            'role' => 'staff'
        ]);

        return redirect()->to('/login')->with('success', '✅ Account created successfully! You can now log in.');
    }
}
