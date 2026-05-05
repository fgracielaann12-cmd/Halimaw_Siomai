<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturnModel extends Model
{
    protected $table            = 'returns';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'transaction_id',
        'item_id',
        'variation',
        'quantity',
        'reason',
        'evidence_path',
        'return_condition',
        'action_taken',
        'processed_by',
        'created_at'
    ];

    protected $useTimestamps = false;
}
