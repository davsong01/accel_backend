<?php

namespace App\Models;

use App\Traits\CompanyIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory, CompanyIdTrait;
    protected $guarded = [];
}
