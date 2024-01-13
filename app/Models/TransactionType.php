<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'customer_details', 'customer_contract_details', 'staff_details', 'company_id'];

    public function purposes(){
        return $this->hasMany(TransactionPurpose::class);
    }
}
