<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Package;
use App\Models\StaffRole;

use Illuminate\Support\Str;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Add Permission
        // 
        // First Instalment Payment to generate Provisional Allocation Letter (General Manager)
        // Allocation (General Manager/MD)
        // Change of Ownership (General Manager)
        // Property Change (General Manager)
        // Customer Refund (General Manager/MD)
        // Contract Cancellation (General Manager)
        // Clearance (General Manager/MD)
        // Regenerate Legal Document (General Manager)
        // Every Construction Payment (General Manager/Project Manager)
        // Add Estate (General Manager)

        $newRoles = [
            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Submit Application Form 2',
                'description' => 'Submit Application Form 2 (Marketing Manager)',
                'slug' => 'application.form.submit',
                'route' => 'application.form.submit',
            ]
        ];
        
        foreach($newRoles as $rols){
            DB::table('role_permissions')->updateOrInsert($rols);
        }
        
        $allAccess = RolePermission::pluck('id')->toArray();
        $access = '';

        if($allAccess){
            $access = implode(',', $allAccess);
        }
        
        $package = Package::where('id', 1)->update([
            'access' => $access,
        ]);

        $adminDefault = StaffRole::where('staff_id', 1)->first();
        $role = Role::where('id', $adminDefault->role_id)->update([
            'access' => $access,
        ]);

    }
}
