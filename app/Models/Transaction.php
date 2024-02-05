<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Customer;
use App\Models\ChartOfAccount;
use App\Models\CustomerContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'transactions';
    protected $appends = ['receipt_location'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    public function form_payment_contracts()
    {
        return $this->hasMany(CustomerContract::class, 'form_payment_transaction_id', 'transaction_id');
    }

    public function property_payment_contracts()
    {
        return $this->hasMany(CustomerContract::class, 'contract_payment_transaction_id', 'transaction_id');
    }

    public function sei_payment_transactions()
    {
        return $this->hasMany(CustomerContract::class, 'sei_payment_transaction_id', 'transaction_id');
    }

    

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format($value),
        );
    }

    public function getReceiptLocationAttribute()
    {
        if(key_exists('receipt_location', $this->attributes))
            return app('App\Http\Controllers\Controller')->encodeUploadedFile($this->attributes['receipt_location']);
        return '';
    }

    public function getPaymentSlipAttribute()
    {
        if(key_exists('payment_slip', $this->attributes))
            return app('App\Http\Controllers\Controller')->encodeUploadedFile($this->attributes['payment_slip']);
        return '';
    }

}
