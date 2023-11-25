<?php

namespace App\Models;

use App\Models\Currency;
use App\Models\AccountType;
use App\Models\AccountCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChartOfAccount extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function account_category(){
        return $this->belongsTo(AccountCategory::class, 'account_category_id');
    }

    public function account_type()
    {
        return $this->belongsTo(AccountType::class, 'accounttype_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency');
    }
}
