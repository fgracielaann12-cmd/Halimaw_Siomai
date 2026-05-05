<?php

namespace App\Models;

use CodeIgniter\Model;

class PullOutModel extends Model
{
    protected $table = 'pull_outs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_id',
        'variation',
        'quantity',
        'unit_cost',
        'total_loss',
        'pull_out_reason',
        'category',
        'reason_note',
        'image_path',
        'reported_by',
        'date_reported',
        'approved_by',
        'approved_at',
        'status'
    ];

    protected $useTimestamps = false; // We use default CURRENT_TIMESTAMP on DB
}
