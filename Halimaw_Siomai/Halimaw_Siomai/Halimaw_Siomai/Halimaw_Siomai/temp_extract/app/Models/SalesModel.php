<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';

    // ✅ Add 'pack', 'customer_name', and 'customer_email' to allowed fields
    protected $allowedFields = [
        'user_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'payment_method',
        'created_at',
        'pack', // ✅ This was missing!
        'is_seen', // Added for notifications
        'customer_name',
        'customer_email'
    ];

    // Optional: Define return type for better IDE support
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

}