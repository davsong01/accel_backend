<?php

namespace App\Models;

use App\Models\Estate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    protected $fillable = ['name', 'company_id'];
    use HasFactory;

    public function estate(){
        return $this->belongsTo(Estate::class, 'city_id');
    }
}
