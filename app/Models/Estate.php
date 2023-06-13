<?php

namespace App\Models;

use App\Models\City;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estate extends Model
{
    protected $fillable = ['name', 'city_id', 'location_id', 'company_id', 'branch_id', 'property_type']; 
    use HasFactory;

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
