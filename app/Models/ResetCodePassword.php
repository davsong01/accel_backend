<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetCodePassword extends Model
{
    use HasFactory, CompanyIdTrait;

    protected $table = 'password_reset_tokens';
    const UPDATED_AT = null;
    
    protected $fillable = ['token','email'];
}
