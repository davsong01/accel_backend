<?php

namespace App\Models;

use App\Models\City;
use App\Models\Location;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\EstatePropertyTypeDetails;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Estate extends Model
{
    protected $fillable = ['name', 'city_id', 'location_id', 'company_id', 'branch_id', 'property_type', 'bypass_approval','project_supervisor','description', 'status']; 
    use HasFactory;

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function admin(){
        return $this->belongsTo(Admin::class, 'project_supervisor');
    }

    public function estate_property_type(){
        return $this->hasMany(EstatePropertyTypeDetails::class);
    }

    // public function adminUser(): HasOneThrough
    // {
    //     return $this->hasOneThrough(User::class, Admin::class, 'project_supervisor');
    // }


    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

}
