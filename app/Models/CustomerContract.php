<?php

namespace App\Models;

use App\Models\City;
use App\Models\Estate;
use App\Models\PlotSize;
use App\Models\BuildingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerContract extends Model
{
    use HasFactory;
    protected $table = 'customer_contracts';
    protected $guarded = [];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function building_type()
    {
        return $this->belongsTo(BuildingType::class, 'building_type');
    }

    public function plot_size()
    {
        return $this->belongsTo(PlotSize::class, 'plot_size');
    }

    public function building_type_details()
    {
        return $this->belongsTo(BuildingType::class, 'building_type');
    }

    public function plot_size_details()
    {
        return $this->belongsTo(PlotSize::class, 'plot_size');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

}
