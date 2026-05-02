<?php

namespace App\Models;
use CodeIgniter\Model;

class StockRequestModel extends Model
{
    protected $table = 'stock_requests';
    protected $allowedFields = ['user_id', 'item_id', 'quantity', 'action', 'reason', 'status', 'created_at'];
}
