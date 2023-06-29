<?php

namespace App\Models;

use App\Customer;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecialRequest extends Model
{
    use HasFactory;

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function originalowner()
    {
        return $this->belongsTo(Customer::class, 'original_owner_id');
    }

    public function newowner()
    {
        return $this->belongsTo(Customer::class, 'new_owner_id');
    }

}
