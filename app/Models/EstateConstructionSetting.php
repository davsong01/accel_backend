<?php

namespace App\Models;


use App\Traits\CompanyIdTrait;use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstateConstructionSetting extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function building_type(){
        return $this->hasOne(BuildingType::class, 'id', 'building_type_id');
    }
}