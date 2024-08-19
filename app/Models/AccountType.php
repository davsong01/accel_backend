<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use App\Models\ChartOfAccount;
use App\Models\AccountCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountType extends Model
{
    use HasFactory, CompanyIdTrait;
    protected $guarded = [];

    public function account_category(){
        return $this->belongsTo(AccountCategory::class, 'account_category_id');
    }

    public function chartofaccount()
    {
        return $this->hasMany(ChartOfAccount::class, 'accounttype_id');
    }

}