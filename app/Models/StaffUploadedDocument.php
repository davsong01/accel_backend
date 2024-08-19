<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffUploadedDocument extends Model
{
    use HasFactory, CompanyIdTrait;
    protected $guarded = [];

    protected function file(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => url('/') . '/api/uploads/' . base64_encode($value),
        );
    }

    public function setting(){
        return $this->belongsTo(StaffDocumentSetting::class, 'staff_document_id');
    }
}
