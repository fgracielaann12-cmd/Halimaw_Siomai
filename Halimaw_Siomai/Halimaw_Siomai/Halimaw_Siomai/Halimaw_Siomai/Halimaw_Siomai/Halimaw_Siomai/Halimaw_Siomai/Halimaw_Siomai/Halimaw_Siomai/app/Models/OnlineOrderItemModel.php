<?php

namespace App\Models;

use CodeIgniter\Model;

class OnlineOrderItemModel extends Model
{
    protected $table = 'online_order_items';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    protected $allowedFields = [
        'order_id',
        'product_id',
        'product_name',
        'variation',
        'quantity',
        'price',
        'subtotal'
    ];
}
