<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageProvidersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        $insert = [
            [
                'type' => 'email',
                'name' => 'Cpanel Mails',
                'status' => 'inactive',
                'service' => 'smtp', // smtp, others
                'credentials'=> json_encode([
                    
                ]),
                'company_id' => 1,
            ],
            [
                'type' => 'email',
                'name' => 'Gmail',
                'status' => 'active',
                'service' => 'gmail', // smtp, others
                'credentials' => json_encode([
                    'MAIL_DRIVER' => 'smtp',
                    'MAIL_HOST' => 'smtp.gmail.com',
                    'MAIL_PORT' => 465,
                    'MAIL_USERNAME' => 'training.employme@gmail.com',
                    'MAIL_PASSWORD' => 'geswvrqjrwxzrzyi',
                    'MAIL_ENCRYPTION' => 'ssl',
                    'MAIL_FROM_ADDRESS' => 'training.employme@gmail.com',
                    'MAIL_REPLY_TO_NAME' => 'Consode',
                    'MAIL_REPLY_FROM_NAME' => 'Consode',
                    'MAIL_REPLY_FROM_ADDRESS' => 'training.employme@gmail.com',
                    'MAIL_REPLY_TO_ADDRESS' => 'training.employme@gmail.com',
                ]),
                'company_id' => 1,
        
            ]

        ];
        \DB::table('message_providers')->insert($insert);
    
    }
}
