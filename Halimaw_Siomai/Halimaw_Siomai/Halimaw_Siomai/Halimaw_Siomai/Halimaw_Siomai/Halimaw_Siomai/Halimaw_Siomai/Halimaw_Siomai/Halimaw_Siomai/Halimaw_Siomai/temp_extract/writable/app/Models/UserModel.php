<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';

    // ✅ Fields allowed for insert/update
    protected $allowedFields    = ['username', 'email', 'password', 'role'];

    // ✅ Automatically manage created_at & updated_at
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // ✅ Optional: return as array (default)
    protected $returnType       = 'array';

    // ✅ Optional: no soft deletes yet
    protected $useSoftDeletes   = false;
}
