<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLog extends Model
{
    use HasFactory;

    protected $fillable = ['created_by', 'title', 'ip', 'crud_type'];

     /**
     * Get the user for the legal document template.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
