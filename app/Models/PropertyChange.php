<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyChange extends Model
{
    use HasFactory;


    protected $fillable = ['branch_id', 'customer_id', 'customer_contract_id', 'city_id', 'estate_id', 'location_id', 'plot_size_id', 'company_id', 'comment', 'status'];

    protected $with = [
        'contract', 'branch:id,name',
        'customer:id,company_id,branch_id,user_id',
        'customer.user:id,title,first_name,middle_name,last_name,avatar,phone_number',
    ];

    public function contract()
    {
        return $this->belongsTo(CustomerContract::class, 'customer_contract_id');
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

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
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
