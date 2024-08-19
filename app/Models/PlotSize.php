<?php

namespace App\Models;


use App\Traits\CompanyIdTrait;use App\Models\CustomerContract;
use Illuminate\Database\Eloquent\Model;
use App\Models\EstatePropertyTypeDetails;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlotSize extends Model
{
    protected $fillable = ['size', 'company_id'];
    use HasFactory;

    public function estate(){
        return $this->belongsTo(Estate::class);
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    public function customerContract(){
        return $this->hasMany(CustomerContract::class, 'plot_size');
    }

    public function estatePropertyType()
    {
        return $this->hasMany(EstatePropertyTypeDetails::class, 'plot_size');
    }

}
