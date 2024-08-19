<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;use App\Models\PlotSize;
use App\Models\BuildingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstatePropertyTypeDetails extends Model
{
    protected $fillable = ['type','estate_id', 'plot_size', 'building_type', 'estate_price', 'settings_and_excavation', 'infrastructural_fee','status','company_id'];
    
    use HasFactory, CompanyIdTrait;

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    public function plot_details(){
        return $this->belongsTo(PlotSize::class, 'plot_size');
    }

    public function building_details(){
        return $this->belongsTo(BuildingType::class, 'building_type');
    }

}
