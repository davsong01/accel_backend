<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PropertyType::truncate();
        PropertyType::factory()
            ->count(2)
            ->sequence(
                ['type' => 'LAND'],
                ['type' => 'BUILDING']
            )
            ->create();
    }
}
