<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GeneratedLegalDocument extends Model
{
    use HasFactory;

    protected $fillable = [ 'estate_id', 'customer_id', 'regenerate_count', 'file_location', 'company_id'];
    

    protected function fileLocation(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => url('/') . '/api/generated-legal-documents/' . base64_encode($value),
        );
    }

    /**
     * Get the customer that owns the document.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the estate that owns the document.
     */
    public function estate(): BelongsTo
    {
        return $this->belongsTo(Estate::class);
    }

    /**
     * Get the property type that have the document.
     */
    public function propertyType(): HasOne
    {
        return $this->hasOne(PropertyType::class);
    }
    /**
     * Get the document template that have the document.
     */
    public function template(): HasOne
    {
        return $this->hasOne(LegalDocumentTemplate::class, 'id', 'legal_document_template_id');
    }
}
