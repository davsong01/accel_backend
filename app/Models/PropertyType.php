<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PropertyType extends Model
{
    use HasFactory, CompanyIdTrait;

    protected $fillable = ['type'];
    
    /**
     * Get the documents for the property type.
     */
    public function documents(): HasManyThrough
    {
        return $this->hasManyThrough(GeneratedLegalDocument::class, LegalDocumentTemplate::class);
    }

    /**
     * Get the document templates for the property type.
     */
    public function templates(): HasMany
    {
        return $this->hasMany(LegalDocumentTemplate::class);
    }
}
