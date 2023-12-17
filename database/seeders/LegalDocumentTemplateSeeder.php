<?php

namespace Database\Seeders;

use App\Models\Estate;
use App\Models\GeneratedLegalDocument;
use App\Models\LegalDocumentTemplate;
use App\Models\LegalDocumentType;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LegalDocumentTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LegalDocumentTemplate::truncate();

        // generate a default/sample template documents 
        // for each estate

        $estates = Estate::all();
        $legalDocumentTypes = LegalDocumentType::all();

        foreach ($estates as $estate) {
            if (!$estate->documentTemplates->count()) {
                foreach ($legalDocumentTypes as $legalDocumentType) {
                    LegalDocumentTemplate::factory()->create([
                        'estate_id' => $estate->id,
                        'company_id' => $estate->company->id,
                        'created_by' => User::where('company_id', $estate->company->id)->first()->id,
                        'legal_document_type_id' => $legalDocumentType->id,
                        'property_type_id' => $legalDocumentType->property_type_id,
                        'file_location' => 'uploads/legal_document_samples/' . SAMPLE_LEGAL_DOCUMENTS[$legalDocumentType->id],
                    ]);
                }
            }
        }
    }
}
