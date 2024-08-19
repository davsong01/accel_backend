<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDisengagement extends Model
{
    use HasFactory, CompanyIdTrait;

    protected $fillable = ['staff_id', 'created_by', 'reason', 'from', 'to', 'company_id', 'status'];

   /**
     * resource from date.
     */
    protected function from(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => (new Carbon($value))->toDateTimeImmutable(),
        );
    }

   /**
     * resource to date.
     */
    protected function to():Attribute
    {
        return Attribute::make(
            set: fn (string $value) =>  (new Carbon($value))->toDateTimeImmutable(),
        );
    }

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
