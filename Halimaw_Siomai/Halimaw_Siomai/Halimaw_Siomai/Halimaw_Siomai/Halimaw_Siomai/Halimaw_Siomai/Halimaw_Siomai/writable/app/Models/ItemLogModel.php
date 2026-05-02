<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemLogModel extends Model
{
    protected $table = 'item_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['item_id', 'old_data', 'new_data', 'updated_by', 'updated_at'];
    protected $useTimestamps = false;
}
