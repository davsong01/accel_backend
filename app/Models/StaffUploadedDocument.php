<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffUploadedDocument extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected function file(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => url('/') . '/api/documents/' . base64_encode($value),
        );
    }
}
