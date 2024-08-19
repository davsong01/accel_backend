<?php

namespace App\Models;


use App\Traits\CompanyIdTrait;use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffIdentificationSetting extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'staff_identification_settings';
}
