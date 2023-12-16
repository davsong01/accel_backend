<?php

use App\Models\Role;
use App\Models\Package;
use App\Models\StaffRole;
use App\Models\LegalDocumentType;
use App\Models\LegalDocumentTemplate;

define('SAMPLE_LEGAL_DOCUMENTS',[
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

    if (!function_exists("reConstructUploadedProtectedDocument")) {
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
}
