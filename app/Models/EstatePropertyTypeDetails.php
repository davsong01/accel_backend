<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstatePropertyTypeDetails extends Model
{
    protected $fillable = ['type','estate_id', 'plot_size', 'building_type', 'estate_price', 'settings_and_excavation', 'infrastructural_fee','status','company_id'];
    
    use HasFactory;

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

}
