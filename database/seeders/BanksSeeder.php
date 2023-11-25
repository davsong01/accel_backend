<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\GeneralResourceService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        \DB::table('banks')->truncate();
        $insert = GeneralResourceService::getBanks();
        \DB::table('banks')->insert($insert);
    }
}
