<?php

namespace App\Models;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    protected $guarded = [];

    use HasFactory;

    public function staff(){
        return $this->hasMany(Staff::class);
    }

    public function jobtitles()
    {
        return $this->hasMany(JobTitle::class, 'department_id');
    }
}
