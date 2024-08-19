<?php

namespace App\Models;


use App\Traits\CompanyIdTrait;use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveAndAbsenceManagement extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function reliefOfficer()
    {
        return $this->belongsTo(Staff::class, 'delegate_duty');
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    protected function endDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    protected function resumptionDate(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? date("M jS, Y", strtotime($value)) : null,
        );
    }


}
