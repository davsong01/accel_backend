<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionType extends Model
{
    use HasFactory, CompanyIdTrait;

    protected $fillable = ['name', 'customer_details', 'customer_contract_details', 'staff_details', 'company_id'];

    public function purposes(){
        return $this->hasMany(TransactionPurpose::class);
    }

    public function transactions() : HasMany
    {
       return $this->hasMany(Transaction::class);
    }
}
