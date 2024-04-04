<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLog extends Model
{
    use HasFactory;

    protected $fillable = ['created_by', 'title', 'ip', 'crud_type', 'causee_id', 'company_id'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    /**
     * Get the user that created the log.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that the log was created.
     */
    public function causee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causee_id');
    }
}
