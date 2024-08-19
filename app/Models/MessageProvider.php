<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageProvider extends Model
{
    use HasFactory, CompanyIdTrait;

    protected function credentials(): Attribute

    {

        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    } 
}
