<?php

namespace App\Models;

use CodeIgniter\Model;

class PullOutModel extends Model
{
    protected $table = 'pull_outs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_id',
        'quantity',
        'pull_out_reason',
        'reason_note',
        'reported_by',
        'date_reported',
        'status'
    ];

    protected $useTimestamps = false; // We use default CURRENT_TIMESTAMP on DB
}
