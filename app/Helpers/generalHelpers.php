<?php

use App\Models\Role;
use App\Models\Package;
use App\Models\RolePermission;
use App\Models\LegalDocumentType;
use App\Models\LegalDocumentTemplate;

define('SAMPLE_LEGAL_DOCUMENTS', [
    1 => 'land_offer_letter_sample.docx',
    2 => 'building_offer_letter_sample.docx',
    3 => 'land_provisional_letter_sample.docx',
    4 => 'building_provisional_letter_sample.docx',
    5 => 'land_allocation_letter_sample.docx',
    6 => 'building_allocation_letter_sample.docx',
    7 => 'land_change_of_ownership_sample.docx',
    8 => 'building_change_of_ownership_sample.docx',
]);

if (!function_exists("getPackageAccess")) {
    function getPackageAccess()
    {
        $package_ids = Session::get('company_package_ids');
        
        if (!$package_ids) {
            return [];
        }

        $package_ids = explode(',', $package_ids);
        $access = Package::whereIn('id', $package_ids)->pluck('access')->toArray();
        if (!$access) {
            return [];
        }

        $allAccess = [];

        foreach ($access as $acc) {
            $all = explode(',', $acc);

            foreach ($all as $key => $value) {
                if(!empty($value)){
                    $allAccess[] = $value;
                }
            }
        }
        $allAccess = array_unique($allAccess);

        return $allAccess;
    }
}

if (!function_exists("getAllAccess")) {
    function getAllAccess()
    {
        $packageAccess = getPackageAccess();

        // Roles Permissions
        $roleAccess = auth()->user()->staff->roles->pluck('id');
        $rolesAccess = Role::whereIn('id', $roleAccess)->pluck('access');
        $allAccess = [];

        foreach ($rolesAccess as $acc) {
            $all = explode(',', $acc);
            if(!empty($all)){
                foreach($all as $a){
                    if (!empty($a)) $allAccess[] = $a;
                }
            }
        }
        
        // Individual Permissions
        $individual_access = auth()->user()->user_permissions;
        if (!empty($individual_access)) {
            $individual_access = explode(',', $individual_access);
            foreach($individual_access as $access){
                if (!empty($access)) $allAccess[] = $access;
            }    
        }

        $allAccess = array_unique($allAccess);
        
        // we have to make sure these access exist in company package, else yank it off
        $allowedAccess = [];

        foreach ($allAccess as $key => $value) {
            if (!in_array($value, $packageAccess)) {
                unset($all[$key]);
            } else {
                $allowedAccess[] = $value;
            }
        }

        return array_unique($allowedAccess);
    }
}

if (!function_exists("getEncodedFileRealPath")) {
    function getEncodedFileRealPath($file)
    {
        $res = [];
        // $realpath = base_path() . '/' . config('app.upload_path') . '/' . $filename;

        $file = explode('/', $file);
        if (!empty($file)) {
            $file = array_slice($file, -1, 1);

            $full = base64_decode($file[0]);

            $name = explode('/', $full);
            $name = array_slice($name, -1, 1);

            $res = [
                'filename' => $name[0],
                'filepath' => base_path() . '/' . $full,
            ];
        }

        return $res;
    }
}

if (!function_exists("generateEstateLegalDocumentSamples")) {
    function generateEstateDefaultLegalDocument($estate)
    {
        $legal_documents = LegalDocumentType::all();
        $estate_legal_documents = $estate->LegalDocumentTemplates()->pluck('legal_document_type_id');

        foreach ($legal_documents as $document) {
            LegalDocumentTemplate::updateOrCreate(
                ['estate_id' => $estate->id, 'company_id' => $estate->company_id, 'legal_document_type_id' => $document->id],
                [
                    'created_by' => auth()->user()->staff->id,
                    'property_type_id' => $document->property_type_id,
                    'file_location' => 'legal_documents_samples/' . SAMPLE_LEGAL_DOCUMENTS[$document->id],
                ]
            );
        }
    }
}

if (!function_exists("permissionsCheck")) {
    function permissionsCheck($routeName)
    {
        $permissionx = false;
        $error = '';
        $code = '';

        $permission = RolePermission::select('role_ids', 'status', 'id', 'type', 'is_menu')->where(['route' => $routeName, 'type' => auth()->user()->type])->first();

        $user_role_ids = getAllAccess();
        
        if ($permission) {
            $permissionx = true;
            $code = 200;

            // Check status of permission
            if ($permission->is_menu == 'yes' && $permission->status == 'inactive') {
                $permissionx = false;
                $error = 'This resource is not active!';
                $code = 403;
            };


            // $allowed_roles = explode(",", $permission->role_ids);
            // Link permissions to permission id
            $allowed_roles = explode(",", $permission->id);
            $check = !empty(array_intersect($allowed_roles, $user_role_ids));
            
            if (!$check) {
                $permissionx = false;
                $error = 'You are not permitted to use this resource!';
                $code = 403;
            }

            if ($permission->is_menu == 'no' && $permission->status == 'inactive') {
                $permissionx = true;
                $code = 200;
            };
            
        } else {
            $permissionx = true;
            $code = 200;
        }
        
        return [
            'permission' => $permissionx,
            'error' => $error,
            'code' => $code,
        ];
    }
}

if (!function_exists("generateGenericPaymentReceipt")) {
    function generateGenericPaymentReceipt($payment, $contract){
        $name = $payment->transaction_id;
        $data['receipt_no'] = $name;
        $pathToSave = app('App\Http\Controllers\Controller')->getPathToSaveFile('receipts') . '/' . $name . '.pdf';
        
        $pdf = \PDF::loadView('receipts.generic-payment', compact('payment', 'data', 'contract'))->setPaper('a4', 'portrait');
        
        file_put_contents(base_path() . '/' . config('app.upload_path') . '/' . $pathToSave, $pdf->output());
        // Rmember to create a public folder in the root directory if there is a PDF error
        return [
            'receipt_no' => $name . '.pdf',
            'filename' => $name . '.pdf',
            'filepath' => config('app.upload_path') . '/' . $pathToSave
        ];
    }
}