<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractPaymentThread extends Model
{
    use HasFactory, CompanyIdTrait;
    protected $guarded = [];

    // protected function payment_slip(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value = null) => !empty($value) ? app('App\Http\Controllers\Controller')->encodeGeneralUploadedFile($value) : '',
    //     );
    // }

    // private function receipt_location(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value = null) => !empty($value) ? app('App\Http\Controllers\Controller')->encodeGeneralUploadedFile($value) : '',
    //     );
    // }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'transaction_id', 'transaction_id');
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y h:i a", strtotime($value)),
        );
    }
}
