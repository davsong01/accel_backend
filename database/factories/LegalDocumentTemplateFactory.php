<?php

namespace Database\Factories;


use App\Models\LegalDocumentType;
use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LegalDocumentTemplate>
 */
class LegalDocumentTemplateFactory extends Factory
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
            'company_id' => 1,
            'created_by' => 1,
            // 'legal_document_type_id' => LegalDocumentType::factory()->create()->id,
            // 'property_type_id' => PropertyType::factory()->create()->id,
            'file_location' => fake()->url()
        ];
    }
}
