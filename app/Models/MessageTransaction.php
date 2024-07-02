<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
       'medium',
       'name',
       'recipient',
       'attachments',
       'message',
       'subject',
       'schedule_date',
       'company_id',
       'provider_id',
        'status',
        'purpose'
    ];
}
