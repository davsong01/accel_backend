<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commission extends Model
{
    use HasFactory, CompanyIdTrait;

    protected $guarded = [];

    public function staff(){
        return $this->hasMany(Staff::class);
    }
}
