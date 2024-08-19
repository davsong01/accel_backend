<?php

namespace App\Models;


use App\Traits\CompanyIdTrait;use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetStarted extends Model
{
    use HasFactory;

    protected $table = 'get_started';

    protected $fillable = ['full_name', 'email', 'company_name', 'company_size', 'industry', 'phone_number'];
}
