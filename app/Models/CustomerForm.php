<?php

namespace App\Models;

use App\Models\Lga;
use App\Models\States;
use App\Models\Country;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerForm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'customer_forms';
    protected $appends = ['lga','country','state','form_name'];

    public function getLgaAttribute()
    {
        return $this->customer->user->lga;
    }

    public function getCountryAttribute()
    {
        return $this->customer->user->country;
    }

    public function getFormNameAttribute()
    {
        return 'Form-'.$this->id;
    }

    public function getStateAttribute()
    {
        return $this->customer->user->state;
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    protected function dateOfBirth(): Attribute{
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }
    
    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function scopeIsCustomerResponse($query){
        return $query->where('is_customer_response', 1); 
    }

    public function scopeIsForm2Request($query){
        return $query->where('is_form2_request', 1);
    }
}
