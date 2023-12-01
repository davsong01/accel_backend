<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function staff()
    {
        return $this->belongsToMany(Staff::class,'staff_roles', 'role_id', 'staff_id');
    }


    // public function department()
    // {
    //     return $this->belongsTo(Department::class);
    // }

    protected function access(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null) => !empty($value) ? explode(',',$value) : null,
        );
    }

}
