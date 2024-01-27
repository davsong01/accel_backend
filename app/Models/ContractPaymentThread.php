<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractPaymentThread extends Model
{
    use HasFactory;
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

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }
}
