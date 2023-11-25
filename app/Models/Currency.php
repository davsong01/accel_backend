<?php

namespace App\Models;

use App\Models\ChartOfAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    public function chartofaccount()
    {
        return $this->hasMany(ChartOfAccount::class, 'currency');
    }
}
