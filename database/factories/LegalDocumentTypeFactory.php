<?php

namespace Database\Factories;

use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LegalDocumentType>
 */
class LegalDocumentTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['OFFER_LETTER','PROVISIONAL_LETTER','ALLOCATION_LETTER','CHANGE_OF_OWNERSHIP_LETTER']),
            'property_type_id' => PropertyType::factory()->create()->id,
            'company_id' => 1,
        ];
    }
}
