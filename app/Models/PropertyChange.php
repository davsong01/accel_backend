<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyChange extends Model
{
    use HasFactory;


    protected $fillable = ['branch_id', 'customer_id', 'original_customer_contract_id', 'new_customer_contract_id', 'company_id', 'comment', 'status'];

    protected $with = [
        'original_contract', 'new_contract', 'branch',
        'customer:id,company_id,branch_id,user_id',
        'customer.user:id,title,first_name,middle_name,last_name,avatar,phone_number',
    ];

    public function original_contract()
    {
        return $this->belongsTo(CustomerContract::class, 'original_customer_contract_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class, 'estate_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function special_request()
    {
        return $this->morphOne(SpecialRequest::class, 'special_requestable');
    }
}
