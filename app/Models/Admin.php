<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    public function adminRoles(){
        return $this->hasMany(AdminRole::class);
        // $admin_roles = AdminRole::whereAdminId($this->id);
        // dd('dsd', $admin_roles);
    }

    public function roles()
    {
        return $this->hasMany(AdminRole::class);
    }

}

