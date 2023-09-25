<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'payments';

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }
}
