<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Company
        $company = \App\Models\Company::Create([
            'slug' => 'local',
            'name' => 'Consode Services Limited',
            'location' => 'Abuja',
            'url' => 'https://consode.com'
        ]);

        $company2 = \App\Models\Company::Create([
            'slug' => 'TechDave',
            'name' => 'TechDave and Sons',
            'location' => 'Lagos',
            'url' => 'https://techdaves.com'
        ]);

        // Branch
        $branch = \App\Models\Branch::Create([
            'company_id' => $company2->id,
            'name' => 'Ikeja Branch',
            'location' => 'Computer Village, Ikeja'
        ]);

        // Role
        $role1 = \App\Models\Role::Create([
            'name' => 'Super Admin',
            'type' => 'admin',
        ]);

        $role2 = \App\Models\Role::Create([
            'name' => 'Default',
            'type' => 'customer',
        ]);

        // Users
        $superAd = \App\Models\User::Create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'type' => 'admin',
            'status' => 'active',
            'email' => 'test@example.com',
            'password' => Hash::make('123456789'),
        ]);

        // Admin
        $superAdmin = \App\Models\Admin::Create([
            'user_id' => $superAd->id,
            'status' => 'active'
        ]);

        // Role Admin
        $roleAdmin = \App\Models\AdminRole::Create([
            'admin_id' => $superAdmin->id,
            'role_id' => $role1->id,
        ]);

        // Role Permission
        $rolePermission =  \App\Models\RolePermission::Create([
            'role_ids'=>$role1->id,
            'status' => 'active',
            'type' => 'admin',
            'is_menu'=> 'yes',
            'menu_name'=> 'Dashboard',
            'slug'=> 'dashboard',
            'route'=>'dashboard',
            'icon'=>'dashboard',
            'order'=>'A',
            'company_id'=>$company->id,
        ]);
       
        // other Users
        $range = range(1,500);
        foreach($range as $r){
            $use = \App\Models\User::create([
                'first_name' => Str::random(10),
                'last_name' => Str::random(10),
                'status' => 'active',
                'type' => 'customer',
                'email' => Str::random(10).'@gmail.com',
                'password' => Hash::make('123456789'),
            ]);

            \App\Models\Customer::create([
                'company_id' => $company2->id,
                'branch_id'=>$branch->id,
                'user_id'=>$use->id,
                'type'=>'lead'
            ]);

            $roleCustomer = \App\Models\CustomerRole::Create([
                'customer_id' => $use->id,
                'role_id' => $role2->id,
            ]);
        }
    }
}
