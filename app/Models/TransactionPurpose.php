<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionPurpose extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'transaction_type_id', 'company_id'];

    public function TransactionType(){
       return $this->belongsTo(TransactionType::class);
    }

    public function transactions(): HasMany
    {
       return $this->hasMany(Transaction::class);
    }
}
