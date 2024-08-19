<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeExit extends Model
{
    use HasFactory, CompanyIdTrait;

    protected $guarded = [];

    protected function file(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null) => !empty($value) ? url('/') . '/uploads/' . base64_encode($value) : '',
        );
    }

    /**
     * Get the staff that wants to exit.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
