<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\GeneralResourceService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        \DB::table('currencies')->truncate();

        $insert = GeneralResourceService::getCurrencies();
        foreach($insert as $key=>$value){
            $value['identifier'] = $key;   
            \DB::table('currencies')->insert($value);

        }
    }
}
