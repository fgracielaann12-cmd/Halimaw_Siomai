<?php

namespace App\Models;
use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'product_id',
        'name',
        'quantity',
        'price',
        'expiration_date',
        'barcode',
        'category',
        'subcategory',
        'auto_delete',
        'status',         // ✅ Add this
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = false;
}
