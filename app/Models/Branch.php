<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $guarded = [];

    use HasFactory, CompanyIdTrait;

    protected $table = 'branches';

    public function staff() : HasMany
    {
        return $this->hasMany(Staff::class);
    }
}
