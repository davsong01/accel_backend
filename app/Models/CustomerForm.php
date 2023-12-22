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

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    protected function dateOfBirth(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(States::class);
    }

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
