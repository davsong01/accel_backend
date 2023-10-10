<?php

namespace App\Models;

use App\Models\User;
use App\Models\JobType;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\LeaveAndAbsenceManagement;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;
    protected $guarded = [];
    
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

    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function job_type()
    {
        return $this->belongsTo(JobType::class, 'jobtype_id');
    }

    public function job_role()
    {
        return $this->belongsTo(JobRole::class, 'jobrole_id');
    }

    public function leave(){
        return $this->hasMany(LeaveAndAbsenceManagement::class);
    }

    public function current_approved_leave()
    {
        return $this->hasMany(LeaveAndAbsenceManagement::class)->where('status', 'approved')->where('end_date', '>=', Carbon::now()->toDateTimeString());

    }
    
}
