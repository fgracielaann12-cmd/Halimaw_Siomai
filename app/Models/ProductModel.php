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
        'sku',
        'name',
        'category',
        'quantity',           // ← This is your stock column
        'expiration_date',
        'auto_delete',
        'status',
        'image',              // ← For POS images
        'image_path',
        'price',              // ← For non-siomai items
        'price_12',           // ← For siomai packs
        'price_20',
        'price_40',
        'pack_small_price',
        'pack_medium_price',
        'pack_biggest_price',
        'is_variation_child',
        'variation_group_id',
        'variation_label'
    ];
}
