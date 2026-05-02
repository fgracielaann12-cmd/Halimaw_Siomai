<?php

namespace App\Models; // ✅ Make sure folder name is "Models"

use CodeIgniter\Model;

class DeleteModel extends Model
{
    protected $table = 'deleted_items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_id',
        'name',
        'category',
        'subcategory',
        'quantity',
        'price',
        'expiration_date',
        'barcode',
        'auto_delete',
        'status',
        'created_at',
        'deleted_at'
    ];

    protected $useTimestamps = false;
}
