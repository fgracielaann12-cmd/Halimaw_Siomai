<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserManagement extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    /**
     * Display list of users
     */
    public function index()
    {
        $data = [
            'title' => 'Staff Management',
            'users' => $this->userModel->orderBy('id', 'ASC')->findAll()
        ];

        return view('user/admin/users/index', $data);
    }

    /**
     * Show create staff form
     */
    public function create()
    {
        $data = ['title' => 'Register Staff'];
        return view('user/admin/users/register', $data);
    }

    /**
     * Save new staff
     */
    public function save()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'), // hashed automatically by model
            'role'     => 'staff' // default role for all new staff
        ]);

        return redirect()->to('/admin/staff/users')->with('success', '✅ Staff added successfully');
    }

    /**
     * Show edit staff form
     */
    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/staff/users')->with('error', '⚠️ Staff not found');
        }

        return view('user/admin/users/edit', [
            'title' => 'Edit Staff',
            'user' => $user
        ]);
    }

    /**
     * Update existing staff
     */
    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/staff/users')->with('error', '⚠️ Staff not found');
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]"
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => 'staff' // keep role as staff
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password'); // hashed automatically by model
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/admin/staff/users')->with('success', '✅ Staff updated successfully');
    }

    /**
     * Delete staff
     */
    public function delete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/admin/staff/users')->with('success', '✅ Staff deleted successfully');
    }
}
