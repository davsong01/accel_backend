<?php

namespace App\Models;


use App\Traits\CompanyIdTrait;use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRole extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }
    
}
