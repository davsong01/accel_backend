<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;use App\Models\CompanySetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory, CompanyIdTrait;

    protected $table = 'companies';

    public function customers(){
        $this->hasMany(Customer::class);
    }

    public function accounts() : HasMany
    {
        return $this->hasMany(ChartOfAccount::class);
    }

    public function settings()
    {
        return $this->hasOne(CompanySetting::class);
    }
}
