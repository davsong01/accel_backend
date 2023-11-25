<?php

namespace Database\Seeders;

use App\Models\BankKeyword;
use Illuminate\Database\Seeder;
use App\Services\GeneralResourceService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BankKeywordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        \DB::table('bank_keywords')->truncate();

        $insert = GeneralResourceService::bankKeywords();
        
        foreach($insert as $key=>$value){
            $enter['bank_code'] = $key;   
            $enter['keywords'] = json_encode($value); 
            BankKeyword::create($enter);
        }
    }
}
