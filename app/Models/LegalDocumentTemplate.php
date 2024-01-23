<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LegalDocumentTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['estate_id', 'company_id', 'created_by', 'legal_document_type_id', 'property_type_id', 'file_location', 'template_sample'];
    protected $appends = ['template_sample_location'];

    protected function fileLocation(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => url('/') . '/api/uploads/' . base64_encode($value ?: $this->template_sample),
        );
    }

    protected function getTemplateSampleLocationAttribute()
    {
       return  $this->template_sample ? url('/') . '/api/uploads/' . base64_encode($this->template_sample) : $this->file_location;
    }

    /**
     * Get the estate for the legal document template.
     */
    public function estate(): BelongsTo
    {
        return $this->belongsTo(Estate::class);
    }

    /**
     * Get the company for the legal document template.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user for the legal document template.
     */
    public function createdBy(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Get the legal document type for the legal document template.
     */
    public function legalDocumentType(): BelongsTo
    {
        return $this->belongsTo(LegalDocumentType::class);
    }

    /**
     * Get the property type for the legal document template.
     */
    public function propertyType(): HasOne
    {
        return $this->hasOne(PropertyType::class);
    }

    /**
     * Get the documents for the legal document template.
     */
    public function generatedLegalDocuments(): HasMany
    {
        return $this->hasMany(GeneratedLegalDocument::class);
    }
}
