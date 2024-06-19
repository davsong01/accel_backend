<?php

namespace App\Models;

use App\Models\Bank;
use App\Models\Role;
use App\Models\User;
use App\Models\JobType;
use App\Models\StaffRole;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\LeaveAndAbsenceManagement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'staff';

    public function staffRoles()
    {
        return $this->hasMany(StaffRole::class, 'staff_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'staff_roles', 'staff_id', 'role_id');
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

    public function scopeRolesList($query){
        $rolesX = [];
        $roles = StaffRole::with('role')->where('staff_id', $this->id)->get();

        foreach($roles as $r){
            if($r->role){
                $rolesX[] =  [
                                'id' => $r->role->id,
                                'name' => $r->role->name,
                                'description' => $r->role->description,
                                'type' => $r->role->type
                            ];

            }

        }

        return $rolesX;
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function pfa()
    {
        return $this->belongsTo(Pfa::class, 'pfa_id');
    }

    public function job_title(){
        return $this->belongsTo(JobTitle::class, 'job_title_id');
    }

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

}
