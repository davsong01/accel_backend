<?php

namespace App\Models;

use App\Traits\CompanyIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function account_type()
    {
        return $this->hasMany(AccountType::class, 'account_category_id');
    }

}
