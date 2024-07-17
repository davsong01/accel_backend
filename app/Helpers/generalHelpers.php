<?php

use App\Models\Role;
use App\Models\Company;
use App\Models\Package;
use App\Models\Customer;
use App\Models\CompanySetting;
use App\Models\RolePermission;
use App\Models\CustomerContract;
use App\Models\LegalDocumentType;
use App\Http\Controllers\Controller;
use App\Models\LegalDocumentTemplate;
use Illuminate\Support\Facades\Session;

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
                if (!empty($value)) {
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
        $roleAccess = auth()->user()->staff->roles->pluck('id')->toArray();
        $roleAccess[] = (int) env('DEFAULT_ROLE_ID');

        $rolesAccess = Role::whereIn('id', $roleAccess)->pluck('access');
        $allAccess = [];

        foreach ($rolesAccess as $acc) {
            $all = explode(',', $acc);
            if (!empty($all)) {
                foreach ($all as $a) {
                    if (!empty($a)) $allAccess[] = $a;
                }
            }
        }

        // Individual Permissions
        $individual_access = auth()->user()->user_permissions;
        if (!empty($individual_access)) {
            $individual_access = explode(',', $individual_access);
            foreach ($individual_access as $access) {
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
                    'file_location' => 'legal_document_samples/' . SAMPLE_LEGAL_DOCUMENTS[$document->id],
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
    function generateGenericPaymentReceipt($payment, $contract)
    {
        $name = $payment->transaction_id;
        $data['receipt_no'] = $name;
        $pathToSave = app('App\Http\Controllers\Controller')->getPathToSaveFile('receipts') . '/' . $name . '.pdf';

        $pdf = \PDF::loadView('receipts.generic-payment', compact('payment', 'data', 'contract'))->setPaper('a4', 'portrait');

        file_put_contents(base_path() . '/' . config('app.upload_path') . '/' . $pathToSave, $pdf->output());
        // Rmember to create a public folder in the root directory if there is a PDF error
        return [
            'receipt_no' => $name,
            'filename' => $name . '.pdf',
            'filepath' => config('app.upload_path') . '/' . $pathToSave
        ];
    }
}


if (!function_exists("setOptions")) {
    function setOptions()
    {
        $options = [
            'COMPANY_NAME' => 'This will translate to the company name',
            'COMPANY_EMAIL' => 'This will translate to the company support email',
            'COMPANY_URL' => 'This will translate to company website address/url',
            'CUSTOMER_EMAIL' => 'This will translate to the customer\'s email address',
            'CUSTOMER_NAME' => 'This will translate to the customer\'s fullname',
            'CUSTOMER_ADDRESS' =>  'This will translate to the customer\'s address',
            'CUSTOMER_STATE' =>  'This will translate to the customer\'s state',
            'CUSTOMER_PHONE' =>  'This will translate to the customer\'s phone number',
            'DATE' => 'This will translate to the current date of document generation',

            'PLOT_SIZE' => 'This will translate to the contract plot size',
            'BUILDING_TYPE' => 'This will translate to the contract building type name',
            'CONTRACT_ESTATE_NAME' => 'This will translate to the contract estate name',
            'CONTRACT_ESTATE_CITY' => 'This will translate to the contract estate city',
            'CONTRACT_ESTATE_LOCATION' => 'This will translate to the contract estate location',
            'CONTRACT_FORM_APPLICATION_FEE' => 'This will translate to the contract form application fee',
            'CONTRACT_SE_FEE' => 'This will translate to the contract Setting out and excavation fee',
            'CONTRACT_INFRASTRUCTURE_FEE' => 'This will translate to the contract infrastructure',
            
            'CONTRACT_NAME' => 'This will translate to the contract/property name',

            'PROPERTY_NET_VALUE' => 'This will translate to the contract property net value',
            'PROPERTY_DISCOUNT' => 'This will translate to the contract property net value',

            // 'BANK_NAME' => 'This will translate to contract payment account bank name',
            // 'BANK_ACCOUNT_NAME' => 'This will translate to contract payment account name',
            // 'BANK_ACCOUNT_NUMBER' => 'This will translate to contract payment account number',

            // 'COMPANY_REPRESENTATIVE_NAME' => 'This will translate to company representative name',
        ];
        return $options;
    }
}

if (!function_exists("sessionDetails")){
    function sessionDetails($needle){
        if (Session::has($needle)) {
            return Session::get($needle);
        } else {
            return null;
        }
    }
}

if (!function_exists("getOptions")) {
    function getOptions($contract_id, $legalDocumentTemplate){
        $contract = CustomerContract::where('id', $contract_id)->first();
        $customer = Customer::with(['user:id,email,first_name,middle_name,last_name,residential_address,state_id,phone_number'])->where('id', $contract->customer_id)->first();
        $company = Company::with('settings')->where('id', session()->get('company_id'))->first();
        $settings = $company->settings;
       
        $discount = $contract->discount ?? 0;
        $property_price = $contract->property_type_details->estate_price;
        $se_fee = $contract->property_type_details->settings_and_excavation;
        $infrastructural_fee = $contract->property_type_details->infrastructural_fee;
        
        $user = $customer->user;
        $options = setOptions();
        $values = [];

        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $values['CUSTOMER_NAME'] = key_exists('CUSTOMER_NAME', $options) ? $user->fullname : null;
                $values['DATE'] = key_exists('DATE', $options) ? date("M jS, Y", strtotime(now())) : null;
                $values['CUSTOMER_PHONE'] = key_exists('CUSTOMER_PHONE', $options) ? $user->phone_number : null;
                $values['COMPANY_NAME'] = key_exists('COMPANY_NAME', $options) ? session()->get('company_name') : null;
                $values['COMPANY_URL'] = key_exists('COMPANY_URL', $options) ? session()->get('company_url') : null;
                $values['COMPANY_EMAIL'] = key_exists('COMPANY_EMAIL', $options) ? $settings->company_email : null;
                
                $values['CUSTOMER_EMAIL'] = key_exists('CUSTOMER_EMAIL', $options) ? $user->email : null;
                $values['CUSTOMER_ADDRESS'] = key_exists('CUSTOMER_ADDRESS', $options) ? $user->residential_address : null;
                $values['CUSTOMER_STATE'] = key_exists('CUSTOMER_STATE', $options) ? $user->state->name : null;

                $values['PLOT_SIZE'] = key_exists('PLOT_SIZE', $options) ? $contract->plotSize->size ?? '' : null;
                $values['CONTRACT_ESTATE_NAME'] = key_exists('CONTRACT_ESTATE_NAME', $options) ? ($contract->estate-> name ?? '') : null;
                $values['CONTRACT_ESTATE_CITY'] = key_exists('CONTRACT_ESTATE_CITY', $options) ? ($contract->estate->location->city->name ?? '') : null;
                $values['CONTRACT_ESTATE_LOCATION'] = key_exists('CONTRACT_ESTATE_LOCATION', $options) ? ($contract->estate->location->name ?? ''): null;
                $values['CONTRACT_FORM_APPLICATION_FEE'] = key_exists('CONTRACT_ESTATE_LOCATION', $options) ? $settings->currencyData->symbol .number_format($contract->form_transactions->sum('amount')) : null;
                $values['PROPERTY_NET_VALUE'] = key_exists('PROPERTY_NET_VALUE', $options) ? $settings->currencyData->symbol . number_format($property_price) : null;
                $values['PROPERTY_DISCOUNT'] = key_exists('PROPERTY_DISCOUNT', $options) ? $settings->currencyData->symbol . number_format($discount) : null;
                $values['CONTRACT_SE_FEE'] = key_exists('CONTRACT_SE_FEE', $options) ? $settings->currencyData->symbol . number_format($se_fee) : null;
                $values['CONTRACT_INFRASTRUCTURE_FEE'] = key_exists('CONTRACT_INFRASTRUCTURE_FEE', $options) ? $settings->currencyData->symbol . number_format($infrastructural_fee) : null;
                $values['CONTRACT_NAME'] = key_exists('CONTRACT_NAME', $options) ? ($contract->contract_name ?? ''): null;

                $values['BUILDING_TYPE'] = key_exists('BUILDING_TYPE', $options) ? $contract->building_type_details?->name : null;
            }
        }
        
        return $values;
    }
}