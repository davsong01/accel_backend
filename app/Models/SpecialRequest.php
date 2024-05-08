<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecialRequest extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'company_id'];

    protected $with = ['special_requestable'];

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

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format($value),
        );
    }

    public function special_requestable()
    {
        return $this->morphTo();
    }

}
