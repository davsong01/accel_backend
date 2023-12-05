<?php

namespace Database\Factories;

use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GeneratedLegalDocument>
 */
class GeneratedLegalDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'estate_id' => 5,
           'customer_id' => 508,
           'legal_document_type_id' => 1,
            'property_type_id' => PropertyType::factory()->create()->id,
           'regenerate_count' => 1,
           'company_id' => 1
        ];
    }
}
