<?php

namespace App\Models;

use App\Traits\CompanyIdTrait;
use App\Models\AccountCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountGroup extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function categories(){
        return $this->hasMany(AccountCategory::class, 'account_group_id');
    }
}
