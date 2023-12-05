<?php

namespace Database\Seeders;


use App\Models\GeneratedLegalDocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneratedLegalDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GeneratedLegalDocument::truncate();
        GeneratedLegalDocument::factory()
        ->count(5)
        // ->has(PropertyType::factory())
        // ->has(LegalDocumentTemplate::factory())
        ->create();
    }
}
