<?php

namespace App\Models;

use App\Models\User;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\CustomerRole;
use App\Models\CustomerContract;
use App\Models\CustomerProperty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function roles()
    {
        return $this->hasMany(CustomerRole::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function properties()
    {
        return $this->hasMany(CustomerProperty::class);
    }

    public function contracts()
    {
        return $this->hasMany(CustomerContract::class);
    }

    public function singleContract()
    {
        return $this->hasMany(CustomerContract::class, 'customer_id')->where('contract_type','Single');
    }

    public function jointContract()
    {
        return $this->hasMany(CustomerContract::class, 'customer_id')->where('contract_type','Joint');
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    public function generatedLegalDocuments(){
        return $this->hasMany(GeneratedLegalDocument::class);
    }

    public function payments()
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }

    public function getMeansOfIdentificationAttribute()
    {

        $means_of_identification = !empty($this->attributes['means_of_identification']) ? json_decode($this->attributes['means_of_identification'], true) : [];

        $means_of_identification['occupation'] = isset($means_of_identification['occupation']) ? $means_of_identification['occupation'] : null;
        $means_of_identification['place_of_work'] = isset($means_of_identification['place_of_work']) ? $means_of_identification['place_of_work'] : null;
        $means_of_identification['id_card_type'] = isset($means_of_identification['id_card_type']) ? $means_of_identification['id_card_type'] : null;

        $means_of_identification['passport_photo'] = isset($means_of_identification['passport_photo']) ? app('App\Http\Controllers\Controller')->encodeGeneralUploadedFile($means_of_identification['passport_photo']) : null;

        $means_of_identification['id_card'] = isset($means_of_identification['id_card']) ? app('App\Http\Controllers\Controller')->encodeUploadedFile($means_of_identification['id_card']) : null;

        $means_of_identification['signature'] = isset($means_of_identification['signature']) ? app('App\Http\Controllers\Controller')->encodeUploadedFile($means_of_identification['signature']) : null;

        return $means_of_identification;
    }

    public function getNextOfKinAttribute()
    {
        $next_of_kin = !empty($this->attributes['next_of_kin']) ? json_decode($this->attributes['next_of_kin'], true) : [];

        return $next_of_kin;
    }

}
