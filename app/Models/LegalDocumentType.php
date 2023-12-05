<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LegalDocumentType extends Model
{
    use HasFactory;

    protected $fillable = ['property_type_id', 'type'];

    /**
     * Get the documents for the legal document type.
     */
    public function generatedLegalDocuments(): HasMany
    {
        return $this->hasMany(GeneratedLegalDocument::class);
    }

    /**
     * Get the document templates for the legal document type.
     */
    public function legalDocumentTemplates(): HasMany
    {
        return $this->hasMany(LegalDocumentTemplate::class);
    }

    /**
     * Get the property type for the legal document type.
     */
    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }
}
