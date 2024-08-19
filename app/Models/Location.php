<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;use App\Models\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory, CompanyIdTrait;
    protected $fillable = ['name', 'company_id','city_id'];


    public function city(){
        return $this->belongsTo(City::class);
    }
}
