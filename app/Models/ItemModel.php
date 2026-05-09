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
        'sku',
        'image_path',
        'quantity',
        'price',
        'expiration_date',
        'barcode',
        'category',
        'subcategory',
        'auto_delete',
        'status',
        'pack_small_qty',
        'pack_medium_qty',
        'pack_biggest_qty',
        'pack_small_price',
        'pack_medium_price',
        'pack_biggest_price',
        'created_at',
        'is_expiring_seen',
        'is_expired_seen',
        'is_variation_child',
        'variation_group_id',
        'variation_label'
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = false;
}
