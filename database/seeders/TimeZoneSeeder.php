<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\GeneralResourceService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TimeZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        \DB::table('timezones')->truncate();

        $insert = GeneralResourceService::timeZones();
        
        foreach($insert as $key=>$value){
            $value['id'] = $key;
            $value['utc'] = json_encode($value['utc']);

            \DB::table('timezones')->insert($value);
        }
    }
}
