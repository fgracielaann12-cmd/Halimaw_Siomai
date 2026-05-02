<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        $data['title'] = 'User Management';
        return view('admin/users/index', $data);
    }

   public function create()
{
    $data['title'] = 'Register User';
    return view('admin/users/register', $data); // <- new view file
}

public function store()
{
    $username = $this->request->getPost('username');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');
    $confirm = $this->request->getPost('confirm_password');

    if ($password !== $confirm) {
        return redirect()->back()->with('error', 'Passwords do not match');
    }

    $this->userModel->save([
        'username' => $username,
        'email' => $email,
        'password' => $password, // will be hashed automatically via UserModel
        'role' => 'user' // default role, you can change as needed
    ]);

    return redirect()->to('/admin/users')->with('success', 'User registered successfully');
}


    public function edit($id)
    {
        $data['user'] = $this->userModel->find($id);
        $data['title'] = 'Edit User';
        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        $this->userModel->update($id, [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
            'password' => $this->request->getPost('password'), // will hash automatically if not empty
        ]);

        return redirect()->to('/admin/users')->with('success', 'User updated successfully.');
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('success', 'User deleted successfully.');
    }
}
