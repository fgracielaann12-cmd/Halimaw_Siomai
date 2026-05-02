<?php

namespace App\Models;
use CodeIgniter\Model;

class DeletedItemModel extends Model
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
