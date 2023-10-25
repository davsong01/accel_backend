<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvanceAndClaims extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected function proof(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null) => !empty($value) ? url('/') . '/uploads/' . base64_encode($value) : '',
        );
    }

}
