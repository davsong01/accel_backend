<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class GeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // \DB::table('packages')->truncate();

        $package = \App\Models\Package::Create([
            'name' => 'All Packages',
            'access' => '2,3,4,5,6,7,8,9,10,11,12,13,14,15,1,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60',
            'description' => 'This contains all access',
        ]);

        // Create Company
        $company = \App\Models\Company::Create([
            'slug' => 'local',
            'name' => 'Consode Services Limited',
            'location' => 'Abuja',
            'url' => 'https://consode.com',
            'package_ids' => $package->id
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
            'name' => 'Super Staff',
            'type' => 'staff',
            'access' => '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66',
        ]);

        $role2 = \App\Models\Role::Create([
            'name' => 'Default',
            'type' => 'customer',
            'access' => '',
        ]);

        // Users
        $superAd = \App\Models\User::Create([
            'first_name' => 'Super',
            'last_name' => 'Staff',
            'type' => 'Staff',
            'status' => 'active',
            'gender' => 'Male',
            'username' => 'Accel Admin',
            'staff_id' => '12345',
            'email' => 'test@example.com',
            'user_permissions' => '1,2,4',
            'password' => Hash::make('123456789'),
        ]);

        // Staff
        $superAdmin = \App\Models\Staff::Create([
            'user_id' => $superAd->id,
            'department_id' => 1,
            'status' => 'active'
        ]);

        // Role Staff
        $roleAdmin = \App\Models\StaffRole::Create([
            'staff_id' => $superAdmin->id,
            'role_id' => $role1->id,
        ]);

        // Role Permission
        $rolePermission =  \App\Models\RolePermission::Create([
            'role_ids' => $role1->id,
            'status' => 'active',
            'type' => 'Staff',
            'is_menu' => 'yes',
            'menu_name' => 'Dashboard',
            'slug' => 'dashboard',
            'route' => 'dashboard',
            'icon' => 'dashboard',
            'order' => 'A',
            'company_id' => $company->id,
        ]);

        // other Users
        // $range = range(1, 500);
        // foreach ($range as $r) {
        //     $use = \App\Models\User::create([
        //         'first_name' => Str::random(10),
        //         'last_name' => Str::random(10),
        //         'status' => 'active',
        //         'type' => 'customer',
        //         'email' => Str::random(10) . '@gmail.com',
        //         'password' => Hash::make('123456789'),
        //     ]);

        //     \App\Models\Customer::create([
        //         'company_id' => $company2->id,
        //         'branch_id' => $branch->id,
        //         'user_id' => $use->id,
        //         'type' => 'lead'
        //     ]);

        //     $roleCustomer = \App\Models\CustomerRole::Create([
        //         'customer_id' => $use->id,
        //         'role_id' => $role2->id,
        //     ]);
        // }
    }
}
