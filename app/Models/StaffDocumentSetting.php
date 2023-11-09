<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffDocumentSetting extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'staff_document_settings'; 
    
}
