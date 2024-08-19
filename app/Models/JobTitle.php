<?php

namespace App\Models;


use App\Traits\CompanyIdTrait;use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'department_id', 'company_id'];

    /**
     * Get the department the resource belongs to
     */
    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Get the company the resource belongs to
     */
    public function company(){
        return $this->belongsTo(Company::class);
    }
}
