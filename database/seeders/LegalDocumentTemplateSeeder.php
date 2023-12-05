<?php

namespace Database\Seeders;

use App\Models\GeneratedLegalDocument;
use App\Models\LegalDocumentTemplate;
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
        LegalDocumentTemplate::factory()
        ->count(5)
        ->has(GeneratedLegalDocument::factory()->count(4))
        ->create();
    }
}
