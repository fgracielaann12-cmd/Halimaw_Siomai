<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'role', 'password'];
    protected $useTimestamps = true; // auto-manage created_at & updated_at

    // Auto-hash password before insert/update
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hash password if provided
     */
    protected function hashPassword(array $data)
    {
        if (!empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['data']['password']); // skip empty password on update
        }
        return $data;
    }

    /**
     * Find user by username or email
     */
    public function findByLogin(string $login)
    {
        return $this->where('username', $login)
                    ->orWhere('email', $login)
                    ->first();
    }
}
