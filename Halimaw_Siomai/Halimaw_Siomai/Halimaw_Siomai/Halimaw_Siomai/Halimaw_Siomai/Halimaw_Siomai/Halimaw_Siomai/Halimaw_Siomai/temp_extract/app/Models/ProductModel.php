<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'items'; // Now uses your real inventory table
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'product_id',
        'name',
        'category',
        'quantity',           // ← This is your stock column
        'expiration_date',
        'auto_delete',
        'image',              // ← For POS images
        'price',              // ← For non-siomai items
        'price_12',           // ← For siomai packs
        'price_20',
        'price_40'
    ];
}