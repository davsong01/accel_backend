<?php

namespace App\Models;

use App\Models\City;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use App\Models\EstatePropertyTypeDetails;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estate extends Model
{
    protected $fillable = ['name', 'city_id', 'location_id', 'company_id', 'branch_id', 'property_type', 'project_supervisor', 'description', 'status','form_amount'];
    use HasFactory;

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function estate_property_type()
    {
        return $this->hasMany(EstatePropertyTypeDetails::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'project_supervisor');
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    public function contracts($customer_id)
    {
        return $this->hasMany(CustomerContract::class)->select('id')->where('customer_id',$customer_id)->pluck('id')->toArray();
    }

}
