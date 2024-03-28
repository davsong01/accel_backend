<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionPaymentThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'created_by',
        'construction_payment_id',
        'chart_of_account_id',
        'payment_slip',
        'description',
        'amount_paid',
        'balance_before',
        'balance_after',
    ];
}
