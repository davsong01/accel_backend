<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\GeneralResourceService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PfaKeywordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        \DB::table('pfa_keywords')->truncate();

        $insert = [
            '0033' =>  [
                'AIICO',
                'Atico',
                'ATICO Pensions'
            ],
            '0036' =>  [
                'Anchor Pensions',
                'Anchor',
                'IEI-Anchor Pension Managers Limited'
            ],
            '0026' =>  [
                'ARM Pensions',
                'Arm'
            ],
            '0032' =>  [
                'Crusader Sterling Pensions Limited',
                'CSPL Pensions',
                'CSPL',
                'CRUSADERSTERLING',
                'CrusaderSterling Pensions Limited',
                'Crusader',
                'SterlingCrusader Pensions'
            ],
            '0029' =>  [
                'FGPL',
                'FGPL Pensions'
            ],
            '0043' =>  [
                'Fidelity Pensions',
                'Fidelity'
            ],
            '0023' =>  [
                'Leadway Pensure PFA',
                'Leadway',
                'Leadway Pensure'
            ],
            '0031' =>  [
                'NLPC',
                'NLPC Pensions'
            ],
            '0034' =>  [
                'OAK',
                'OAK Pensions'
            ],
            '0025' =>  [
                'PAL',
                'PAL Pensions'
            ],
            '0046' =>  [
                'Radix Pensions',
                'Radix'
            ],
            '0024' =>  [
                'Sigma Pensions',
                'Sigma'
            ],
            '0021' =>  [
                'Stanbic IBTC',
                'Stanbic',
                'Stanbic IBTC Pensions',
                'Stanbic Pensions',
                'IBTC'
            ],
            '0040' =>  [
                'Investment One Pesions',
                'Investment One',
                'Investment'
            ],
            '0037' =>  [
                'APT Pensions',
                'APT'
            ],
            '0022' =>  [
                'Premium Pensions',
                'Premium'
            ],
            '0042' =>  [
                'Veritas Glanvills Pensions',
                'Veritas',
                'Veritas Glanvills',
                'Future Unity Glanvils Pensions Limited'
            ],
            '0035' =>  [
                'Axa Mansard Pensions',
                'Axa Mansard',
                'Axa'
            ],
            '0030' =>  [
                'FCMB Pensions',
                'FCMB',
                'First City Monument Bank'
            ],
            '0028' =>  [
                'Trustfund Pensions',
                'TrustFund',
                'ZPC/TRUSTFUND'
            ]
        ];

        foreach ($insert as $key => $value) {
            $data['code'] = $key;
            $data['keywords'] = implode(',',$value);
            
            \DB::table('pfa_keywords')->insert($data);
        }
       
    }
}
