<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory, CompanyIdTrait;
    protected $guarded = [];

    public function parentMenu(){
        return $this->belongsTo(RolePermission::class, 'parent');
    }

    public function children()
    {
        return $this->hasMany(RolePermission::class, 'parent');
    }
}
