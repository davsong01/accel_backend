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
            $text = $value['symbol'];
            // The regular expression to match Unicode code point escapes
            $regex = '/\\\\u([0-9a-fA-F]{4})/';

            // Replace the Unicode code point escapes with their corresponding UTF-8 characters
            $decoded = preg_replace_callback($regex, function ($match) {
                return mb_convert_encoding('&#x' . $match[1] . ';', 'UTF-8', 'HTML-ENTITIES');
            }, $text);

            $value['symbol'] = $decoded;
            
            \DB::table('currencies')->insert($value);
        }
    }
}
