<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContract extends Model
{
    use HasFactory;
    protected $table = 'customer_contracts';
    protected $guarded = [];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
