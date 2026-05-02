<?php

namespace App\Models;

use CodeIgniter\Model;

class OnlineOrderModel extends Model
{
    protected $table = 'online_orders';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    protected $allowedFields = [
        'order_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_amount',
        'status',
        'created_at'
    ];
}
