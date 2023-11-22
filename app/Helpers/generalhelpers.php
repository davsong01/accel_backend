<?php

use App\Models\Role;
use App\Models\Package;
use App\Models\StaffRole;
if (!function_exists("getPackageAccess")) {
    function getPackageAccess()
    {
        $package_ids = Session::get('company_package_ids');
        if(!$package_ids){
            return [];
        }

        $package_ids = explode(',', $package_ids);
        $access = Package::whereIn('id', $package_ids)->pluck('access')->toArray();
        if(!$access){
            return [];
        }

        $allAccess = [];

        foreach($access as $acc){
            $all = explode(',',$acc);

            foreach($all as $key=>$value){
                $allAccess [] = $value;
            }
        }
        $allAccess = array_unique($allAccess);
        
        return $allAccess;
    }

}

if (!function_exists("getRoleAccess")) {
    function getRoleAccess($individual_access=null)
    {
        $roleAccess = StaffRole::whereIn('id', Session::get('staff_roles'))->pluck('role_id');
        $access = Role::whereIn('id', $roleAccess)->pluck('access')->toArray();

        // we have to make sure these access exist in company package, else yank it off
        $packageAccess = getPackageAccess();
       
        $allowed = [];
       
        if($individual_access){
            // merge with access
        }

        $allAccess = [];

        foreach ($access as $acc) {
            $all = explode(',', $acc);

            foreach ($all as $key => $value) {
                if (!in_array($value, $packageAccess)) {
                    unset($all[$key]);
                }else{
                    $allAccess[] = $value;
                }
            }
        }
        
        $allAccess = array_unique($allAccess);
        
        return $allAccess;

    }

    if (!function_exists("reConstructUploadedFile")) {
        function reConstructUploadedProtectedDocument($file){
            $res = [];
            
            $file = explode('/', $file);
            if(!empty($file)){
                $file = array_slice($file, -1, 1);
                
                $full = base64_decode($file[0]);
               
                $name = explode('/', $full);
                $name = array_slice($name, -1, 1);
                
                $res = [
                    'filename' => $name[0],
                    'filepath' => 'documents/'.$full,
                ];
            }

            return $res;
        }
    }
}