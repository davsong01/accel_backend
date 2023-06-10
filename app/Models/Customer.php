<?php

namespace App\Models;

use App\Models\CustomerRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    public function roles()
    {
        return $this->hasMany(CustomerRole::class);
    }
}
