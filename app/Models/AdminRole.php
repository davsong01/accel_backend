<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use HasFactory;
    protected $fillable = ['role_id','admin_id', 'company_id', 'branch_id'];
    
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
