<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionTypeDefinition extends Model
{
    use HasFactory, CompanyIdTrait;
    protected $guarded = [];
}
