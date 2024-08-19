<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanySetting extends Model
{
    protected $guarded = [];
    // protected $appends = ['logo_path'];

    use HasFactory, CompanyIdTrait;
    
    public function currencyData(){
        return $this->belongsTo(Currency::class, 'currency');
    }

    protected function companyLogo(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null) => !empty($value) ? app('App\Http\Controllers\Controller')->encodeGeneralUploadedFile($value) : '',
        );
    }

    protected function companySecondaryLogo(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null) => !empty($value) ? app('App\Http\Controllers\Controller')->encodeGeneralUploadedFile($value) : '',
        );
    }
}
