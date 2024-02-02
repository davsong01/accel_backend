<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Transaction;
use App\Models\ContractPaymentThread;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractPayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['payment_slip'];


    public function getPaymentSlipAttribute()
    {
        return !empty($this->payment_slip) ? app('App\Http\Controllers\Controller')->encodeGeneralUploadedFile($value) : '';

    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'transaction_id', 'transaction_id');
    }

    public function payment_thread()
    {
        return $this->hasMany(ContractPaymentThread::class, 'contract_payments_id');
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }
}
