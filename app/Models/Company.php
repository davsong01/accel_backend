<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    public function customers(){
        $this->hasMany(Customer::class);
    }

    public function accounts() : HasMany
    {
        return $this->hasMany(ChartOfAccount::class);
    }
}
