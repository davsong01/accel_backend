<?php

namespace Database\Seeders;

use App\Models\LegalDocumentTemplate;
use App\Models\LegalDocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LegalDocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LegalDocumentType::truncate();
        LegalDocumentType::factory()
            ->count(8)
            ->sequence(
                ['name' => 'OFFER_LETTER', 'type' => 'LAND'],
                ['name' => 'OFFER_LETTER', 'type' => 'BUILDING'],
                ['name' => 'PROVISIONAL_LETTER',  'type' => 'LAND'],
                ['name' => 'PROVISIONAL_LETTER',  'type' => 'BUILDING'],
                ['name' => 'ALLOCATION_LETTER',  'type' => 'LAND'],
                ['name' => 'ALLOCATION_LETTER',  'type' => 'BUILDING'],
                ['name' => 'CHANGE_OF_OWNERSHIP_LETTER',  'type' => 'LAND'],
                ['name' => 'CHANGE_OF_OWNERSHIP_LETTER',  'type' => 'BUILDING'],
            )
            // ->for(PropertyType::factory())
            // ->has(LegalDocumentTemplate::factory()->count(4))
            ->create();
    }
}
