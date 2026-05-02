<?php

namespace App\Models;

use CodeIgniter\Model;

class StockRequestLogModel extends Model
{
    protected $table = 'stock_request_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['request_id', 'action', 'message', 'performed_by', 'created_at'];
    protected $useTimestamps = false;
}
