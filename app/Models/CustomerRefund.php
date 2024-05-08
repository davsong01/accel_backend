<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRefund extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'customer_contract_id', 'customer_id', 'termination_charge', 'company_id', 'comment'];

    protected $with = [ 'customer', 'branch', 'contract'];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function contract(){
        return $this->belongsTo(CustomerContract::class, 'customer_contract_id');
    }

    public function special_request()
    {
        return $this->morphOne(SpecialRequest::class, 'special_requestable');
    }
}
