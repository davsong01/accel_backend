<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeOfOwnership extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'original_owner_id', 'customer_contract_id', 'new_owner_id', 'amount', 'company_id', 'comment', 'status'];

    protected $with = [
        'original_owner:id,company_id,branch_id,user_id',
        'original_owner.user:id,title,first_name,middle_name,last_name,avatar,phone_number',
        'new_owner:id,company_id,branch_id,user_id',
        'new_owner.user:id,title,first_name,middle_name,last_name,avatar,phone_number',
        'branch',
        'contract'
    ];

    public function original_owner()
    {
        return $this->belongsTo(Customer::class, 'original_owner_id');
    }

    public function new_owner()
    {
        return $this->belongsTo(Customer::class, 'new_owner_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function contract()
    {
        return $this->belongsTo(CustomerContract::class, 'customer_contract_id');
    }

    public function special_request()
    {
        return $this->morphOne(SpecialRequest::class, 'special_requestable');
    }
}
