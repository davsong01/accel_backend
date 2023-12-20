<?php

namespace App\Models;

use App\Models\User;
use App\Models\Payment;
use App\Models\Property;
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
        return $this->hasMany(Transaction::class, 'id', 'customer_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
