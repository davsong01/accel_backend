<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;
    public function adminRoles()
    {
        return $this->hasMany(StaffRole::class);
        // $staff_roles = StaffRole::whereAdminId($this->id);
        // dd('dsd', $staff_roles);
    }

    public function roles()
    {
        return $this->hasMany(StaffRole::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
