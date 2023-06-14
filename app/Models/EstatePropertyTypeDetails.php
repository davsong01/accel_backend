<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatePropertyTypeDetails extends Model
{
    protected $fillable = ['type','estate_id', 'plot_size', 'building_type', 'estate_price', 'settings_and_excavation', 'infrastructural_fee'];
    
    use HasFactory;
}
