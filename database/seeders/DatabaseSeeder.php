<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Database\Seeders\GeneralSeeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\LgasTableSeeder;
use Database\Seeders\StatesTableSeeder;
use Database\Seeders\CountriesTableSeeder;
use Database\Seeders\MessageProvidersSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            // GeneralSeeder::class,
            // CountriesTableSeeder::class,
            // StatesTableSeeder::class,
            // LgasTableSeeder::class,
            // MessageProvidersSeeder::class
        ]);
      
    }
}
