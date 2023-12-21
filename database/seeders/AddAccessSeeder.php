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
        //  (General Manager)
        //  (General Manager/MD)
        //  (General Manager)
        //  (General Manager)
       

        $newRoles = [
            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Submit Application Form 2',
                'description' => 'Submit Application Form 2 (Marketing Manager)',
                'slug' => 'application.form.submit',
                'route' => 'application.form.submit',
            ],
            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Make First Instalment Payment',
                'description' => 'First Instalment Payment to generate Provisional Allocation Letter',
                'slug' => 'first.instalment.payment',
                'route' => 'first.instalment.payment',
            ],
            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Perform Allocation',
                'description' => 'Allocation',
                'slug' => 'allocate',
                'route' => 'allocate',
            ],
            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Change of Ownership',
                'description' => 'Change of Ownership',
                'slug' => 'change.of.ownership',
                'route' => 'change.of.ownership',
            ],
            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Change of Ownership',
                'description' => 'Change of Ownership',
                'slug' => 'change.of.ownership',
                'route' => 'change.of.ownership',
            ],
            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Property Change',
                'description' => 'Property Change',
                'slug' => 'property.change',
                'route' => 'property.change',
            ],
            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Customer Refund',
                'description' => 'Customer Refund',
                'slug' => 'customer.refund',
                'route' => 'customer.refund',
            ],
            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Contract Cancellation',
                'description' => 'Contract Cancellation',
                'slug' => 'contract.cancellation',
                'route' => 'contract.cancellation',
            ],
            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Regenerate Legal Document',
                'description' => 'Regenerate Legal Document',
                'slug' => 'regenerate.legal.document',
                'route' => 'regenerate.legal.document',
            ],
            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Regenerate Legal Document',
                'description' => 'Regenerate Legal Document',
                'slug' => 'regenerate.legal.document',
                'route' => 'regenerate.legal.document',
            ],
            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Add Estate',
                'description' => 'Add Estate',
                'slug' => 'estate.store',
                'route' => 'estate.store',
            ],

            [
                'status' => 'active',
                'type' => 'staff',
                'is_menu' => 'no',
                'menu_name' => 'Import Bulk Estate',
                'description' => 'Import Bulk Estate',
                'slug' => 'import.bulk.estate',
                'route' => 'import.bulk.estate',
            ],
            
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
