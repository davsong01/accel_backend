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
        ->count(4)
        ->sequence(
            ['type' => 'OFFER_LETTER'],
            ['type' => 'PROVISIONAL_LETTER'],
            ['type' => 'ALLOCATION_LETTER'],
            ['type' => 'CHANGE_OF_OWNERSHIP_LETTER'],
        )
        // ->for(PropertyType::factory())
        // ->has(LegalDocumentTemplate::factory()->count(4))
        ->create();
    }
}
