<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use App\Models\StaffUploadedDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffDocumentSetting extends Model
{
    use HasFactory, CompanyIdTrait;
    protected $guarded = [];
    protected $table = 'staff_document_settings'; 
    
    public function documents(){
        return $this->hasMany(StaffUploadedDocument::class, 'staff_document_id');
    }
}
