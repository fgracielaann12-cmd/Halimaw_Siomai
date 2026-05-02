<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'transaction_id',
        'user_id',
        'total_amount',
        'payment_method',
        'customer_name',
        'customer_email',
        'vat_applied',
        'vat_type',
        'created_at'
    ];
}
