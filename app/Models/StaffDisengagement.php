<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDisengagement extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'created_by', 'reason', 'from', 'to', 'company_id', 'status'];

    public function createdBy(){
       return $this->belongsTo(Staff::class, 'created_by');
    }

    public function disengaged(){
       return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function staff(){
       return $this->belongsTo(Staff::class, 'staff_id');
    }
}
