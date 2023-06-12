<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlotSize extends Model
{
    protected $fillable = ['size', 'company_id'];
    use HasFactory;
}
