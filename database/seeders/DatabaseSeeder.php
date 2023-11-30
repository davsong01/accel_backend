<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Bank;
use App\Models\Company;
use Illuminate\Support\Str;
use Database\Seeders\PfaSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\BanksSeeder;
use Database\Seeders\GeneralSeeder;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\TimeZoneSeeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\LgasTableSeeder;
use Database\Seeders\PfaKeywordsSeeder;
use Database\Seeders\StatesTableSeeder;
use Database\Seeders\CountriesTableSeeder;
use Database\Seeders\MessageProvidersSeeder;
use PhpOffice\PhpSpreadsheet\Shared\TimeZone;

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
            BanksSeeder::class,
            CurrencySeeder::class,
            BankKeywordsSeeder::class,
            TimeZoneSeeder::class,
            PfaSeeder::class,
            PfaKeywordsSeeder::class,
        ]);
      
    }
}
