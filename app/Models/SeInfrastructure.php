<?php

namespace App\Models;


use App\Traits\CompanyIdTrait;use App\Models\Branch;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeInfrastructure extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected function payment_slip(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null) => !empty($value) ? app('App\Http\Controllers\Controller')->encodeGeneralUploadedFile($value) : '',
        );
    }

    public function customer()
    {
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
}
