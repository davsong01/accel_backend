<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class DefaultRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        try {
            Role::create([
                'id' => env('DEFAULT_ROLE_ID'),
                'access' => '',
                'company_id' => 1,
                'name' => 'Default Role',
                'type' => 'staff'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
        
    }
}
