<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'customer_contract_id', 'certificate', 'status', 'company_id'];

    protected $with = ['customer', 'contract', 'comments'];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function contract() {
        return $this->belongsTo(CustomerContract::class, 'customer_contract_id');
    }

    public function comments(){
        return $this->hasMany(ClearanceThread::class, 'clearance_id')->latest();
    }
}
