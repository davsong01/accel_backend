<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;use App\Models\CustomerContract;
use App\Models\LegalDocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneratedLegalDocument extends Model
{
    use HasFactory, CompanyIdTrait;

    protected $fillable = [ 'estate_id', 'regenerate_count', 'file_location', 'company_id', 'legal_document_template_id', 'contract_id', 'legal_document_type_id'];
    protected $append = ['file_location'];
    

    public function getFileLocationAttribute()
    {
        $file_location = !empty($this->attributes['file_location']) ? app('App\Http\Controllers\Controller')->encodeGeneralUploadedFile($this->attributes['file_location'])
        : '';
        return $file_location;
    }

    /**
     * Get the customer that owns the document.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(CustomerContract::class);
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

    public function legalDocumentType(): HasOne
    {
        return $this->hasOne(LegalDocumentType::class, 'id','legal_document_type_id');
    }

}
