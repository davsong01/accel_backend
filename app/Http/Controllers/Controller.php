<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Lga;
use App\Models\User;
use App\Models\Staff;
use ReflectionMethod;
use App\Models\Estate;
use App\Models\States;
use App\Models\Country;
use App\Models\CompanySetting;
use App\Models\EstateSettings;
use App\Models\AdvanceAndClaims;
use App\Models\CustomerContract;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Models\LeaveAndAbsenceSetting;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Unique;
use App\Notifications\StaffNotification;
use App\Models\LeaveAndAbsenceManagement;
use App\Models\StaffIdentificationSetting;
use App\Models\CustomerIdentificationSetting;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sessionDetails($needle)
    {
        if (Session::has($needle)) {
            return Session::get($needle);
        } else {
            return null;
        }
    }

    public function deleteImage($image)
    {

        if (file_exists(public_path($image))) {
            unlink(public_path($image));
        }

        return;
    }

    public function getContractFormAmount($estate_contracts, $single = null)
    {
        $buildAmount = [];
        $settings = EstateSettings::select(['form_payment_for_land', 'form_payment_for_building'])->where('company_id', $this->sessionDetails('company_id'))->first();

        if (isset($single)) {
            $buildAmount = 0;
            if ($estate_contracts->property_type == 'building') {
                $buildAmount += $settings->form_payment_for_building;
            }

            if ($estate_contracts->property_type == 'land') {
                $buildAmount += $settings->form_payment_for_land;
            }

            return $buildAmount;
        } else {
            foreach ($estate_contracts as $contract) {

                if ($contract->property_type == 'building') {
                    $buildAmount[] = $settings->form_payment_for_building;
                }

                if ($contract->property_type == 'land') {
                    $buildAmount[] = $settings->form_payment_for_land;
                }
            }

            $buildAmount = array_sum(array_unique($buildAmount));
        }

        return $buildAmount;
    }

    public function generateFormPayment(array $contract_ids, $customer_id)
    {
        $contracts = [];
        $ids = [];
        $estates = [];

        foreach ($contract_ids as $contract_id) {
            // Get estates
            $est = CustomerContract::select('estate_id')->where('id', $contract_id)
                ->where('customer_id', $customer_id)
                ->where('company_id', $this->sessionDetails('company_id'))->value('estate_id');

            if (!empty($est)) {
                $estates[] = $est;
                $ids[] = $contract_id;
            }
        }

        $estateContracts = [];

        foreach ($estates as $e) {
            // Get the contracts for each estate
            $es = Estate::where('id', $e)->first();

            $estateContracts[$e] = [
                'estate_id' => $es->id,
                'form_payment' => $es->form_amount,
                'contracts' => $es->contracts($customer_id)
            ];
        }

        $form_payment = [];

        foreach ($estateContracts as $contract) {

            $contract['i_form_payment'] = [];
            $s_ids = array_intersect($contract['contracts'], $ids);
            if (array_diff($contract['contracts'], $ids)) {
                $dif = (count($contract['contracts']) - count(array_diff($contract['contracts'], $ids)));
                $contract['i_form_payment']['form_amount'] = $dif *  $contract['form_payment'];
            } else {
                $contract['i_form_payment']['form_amount'] = $contract['form_payment'];
            }



            $contract['i_form_payment']['contracts'] =  array_values($s_ids);
            $form_payment[$contract['estate_id']] = $contract['i_form_payment'];
        }

        $amount = array_sum(array_column($form_payment, 'form_amount'));
        return [
            'form_payment' => $amount,
            'explanation' => $form_payment
        ];
    }

    public function generateStaffId($date_of_employment = null, $internal = false)
    {
        $date_of_employment = !empty($date_of_employment) ? Carbon::parse($date_of_employment) : Carbon::today();

        $settings = StaffIdentificationSetting::where('company_id', $this->sessionDetails('company_id'))->first();
        $lastUser = User::where('company_id', $this->sessionDetails('company_id'))->where('type', 'staff')->latest()->first();
        $lastUserId = $lastUser->id + 1;
        $lastStaffId = $lastUser->staff_id ?? 1;

        if (!$settings) {
            $id =  sprintf('%06d', $lastUserId);
        } else {
            $id = '';

            if ($settings->use_id_generator == 1) {
                if ($settings->id_generator_type == 'employment_year') {
                    $id = $date_of_employment->format('Y') . sprintf('%06d', $lastUserId);
                }

                if ($settings->id_generator_type == 'employment_month') {
                    $id = $date_of_employment->format('m') . sprintf('%06d', $lastUserId);
                }

                if ($settings->id_generator_type == 'employment_day') {
                    $id = $date_of_employment->format('d') . sprintf('%06d', $lastUserId);
                }

                if ($settings->id_generator_type == 'auto_increment_number') {
                    // $id = $date_of_employment->format('d');
                    $digits = $settings->auto_increment_number_digit;
                    $id = $lastUserId + $digits;

                    return sprintf('%06d', $id);
                }

                if ($settings->id_generator_type == 'random_number') {
                    $count = strlen($lastStaffId);
                    $min = pow(10, $count - 1);
                    $id = mt_rand($min, $min * 9);
                }
            } else {
                $count = strlen($lastStaffId);
                $min = pow(10, $count - 1);
                $id = mt_rand($min, $min * 9);
            }
        }


        if ($internal && $internal == true) {
            return $id;
        }

        return response()->json(['message' => 'Successful', 'data' => $id], 200);
    }

    public function generateCustomerId($isJson = true)
    {
        $created_at = !empty($created_at) ? Carbon::parse($created_at) : Carbon::today();

        $settings = CustomerIdentificationSetting::where('company_id', $this->sessionDetails('company_id'))->first();
        $lastUser = User::where('company_id', $this->sessionDetails('company_id'))->where('type', 'customer')->latest()->first();
        $lastUserId = $lastUser->id + 1;
        $lastCustomerId = $lastUser->customerId ?? 1;

        //     dd($settings);
        //     use_id_generator" => 1
        // "next_auto_increment_number" => "2"
        // "auto_increment_number_digit" => "4"
        // "appearance_format" => "1111"
        // "prefix" => "ACC"
        // "order_of_appearance" => "["prefix","registration_year","registration_month"]"
        if (!$settings) {
            return response()->json(['message' => 'Successful', 'data' => $lastUserId], 200);
        }

        $order_of_appearance = !empty($settings->order_of_appearance) ? json_decode($settings->order_of_appearance, true) : [];
        $id = '';

        if ($settings->use_id_generator == 1) {
            $format = !empty($settings->appearance_format) ? $settings->appearance_format : ' ';

            foreach ($order_of_appearance as $appearance) {
                $slash = end($order_of_appearance) != $appearance ? '/' : '';
                if ($appearance == 'prefix') {
                    $id .= $settings->prefix . $slash;
                }

                if ($appearance == 'registration_year') {
                    $id .= $created_at->format('Y') . $slash;
                }

                if ($appearance == 'registration_month') {
                    $id .= $created_at->format('m') . $slash;
                }

                if ($appearance == 'auto_increment_number') {
                    $digits = $settings->auto_increment_number_digit;
                    $id .= $lastUserId + $digits . $slash;
                }

                if ($appearance == 'random_number') {
                    $count = strlen($format);
                    $min = pow(10, $count - 1);
                    $id .= mt_rand($min, $min * 9) . $slash;
                }
            }
        } else {
            $count = strlen($lastCustomerId);
            $min = pow(10, $count - 1);
            $id = mt_rand($min, $min * 9);
        }

        if (isset($isJson) && $isJson == false) {
            return $id;
        }

        return response()->json(['message' => 'Successful', 'data' => $id], 200);
    }

    public function deleteUploadsFile($imagePath)
    {
        if (file_exists(base_path() . '/' . $imagePath)) {
            unlink(base_path() . '/' . $imagePath);
        }

        return;
    }

    public function uploadFile($file, $location = null, $width = null, $height = null)
    {
        $folder = $this->getPathToSaveFile($location);
        $name = uniqid(11) . '.' . $file->getClientOriginalExtension();

        if (substr($file->getMimeType(), 0, 5) == 'image') {
            $imageFile = Image::make($file);
            $imgWidth = $width ?? $imageFile->width();
            $imgHeight = $imgHeight ?? $imageFile->height();
            
            if (!is_null($imgWidth) && !is_null($imgHeight)) {
                if ($imgWidth < $imgHeight) {
                    $imageFile->resize($imgWidth, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                } else {
                    $imageFile->resize(null, $imgWidth, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }
            
        }

        $imageFile->save(base_path() . '/uploads/' . $folder . '/' . $name);
       
        return $folder . '/' . $name;
    }

    public function uploadProtectedFile($file, $location = null, $width = null, $height = null)
    {
        $folder = $this->getPathToSaveFile($location);
        $name = uniqid(11) . '.' . $file->getClientOriginalExtension();
        
        if (substr($file->getMimeType(), 0, 5) == 'image') {
            $imageFile = Image::make($file);
            $imgWidth = $width ?? $imageFile->width();
            $imgHeight = $imgHeight ?? $imageFile->height();
            if (!is_null($imgWidth) && !is_null($imgHeight)) {
                if ($imgWidth < $imgHeight) {
                    $imageFile->resize($imgWidth, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                } else {
                    $imageFile->resize(null, $imgWidth, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }
        }
       
        $file->storeAs($folder, $name, "uploads");

        return [
            'filename' => $name,
            'filepath' => $folder . '/' . $name
        ];
    }

    public function getPathToSaveFile($location = null)
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        
        if ($location) {
            $location = $location . '/' . $year . '/' . $month . '/' . $day;
        } else {
            $location = $year . '/' . $month . '/' . $day;
        }
        
        if (!is_dir(base_path() . '/uploads/' .$this->sessionDetails('company_slug').'/'. $location)) {
            mkdir(base_path() . '/uploads/' .$this->sessionDetails('company_slug').'/'. $location, 0777, true);
        }

        return $location;
    }

    // public function getPathToSaveFileProtectedFiles($location = null)
    // {
    //     $year = date('Y');
    //     $month = date('m');
    //     $day = date('d');

    //     if ($location) {
    //         $location = $location . '/' . $year . '/' . $month . '/' . $day;
    //     } else {
    //         $location = $year . '/' . $month . '/' . $day;
    //     }

    //     if (!is_dir(base_path(). '/' .  $location)) {
    //         mkdir(base_path() . '/' . $location, 0777, true);
    //     }

    //     return $location;
    // }

    public function deleteFile($file)
    {
        if (Storage::disk('uploads')->exists($file)) {
            Storage::disk('uploads')->delete($file);
        }
    }

    public function getStateDetails($state_id)
    {
        $state = collect([
            'id' => null,
            'name' => 'N/A',
        ]);

        $res = States::select('id', 'name')->where('id', $state_id)->first();
        if ($res) {
            $state = $res;
        }

        return $state;
    }

    public function getCountryDetails($country_id)
    {
        $country = collect([
            'id' => null,
            'name' => 'N/A',
        ]);
        $res = Country::select('id', 'name')->where('id', $country_id)->first();

        if ($res) {
            $country = $res;
        }

        return $country;
    }

    public function getLgaDetails($lga_id)
    {
        $lga = collect([
            'id' => null,
            'name' => 'N/A',
        ]);
        $res = Lga::select('id', 'name')->where('id', $lga_id)->first();

        if ($res) {
            $lga = $res;
        }

        return $lga;
    }

    public function notify($data)
    {
        try {
            $staff = Staff::where('id', $data['staff_id'])->first();
            $user = $staff->user;
            $data['user_id'] = $user->id;

            $user->notify(new StaffNotification($data));
        } catch (\Throwable $th) {
        }
    }

    public static function definedRelations($class, $resource)
    {
        $reflector = new \ReflectionClass('App\Models\\' . $class);

        $methods = array_filter($reflector->getMethods(), function ($methods) use ($class) {
            return str_contains($methods, $class) && $methods->getModifiers() == ReflectionMethod::IS_PUBLIC;
        });

        $counter = 0;
        foreach ($methods as $meth) {
            $first = 'App\Models';
            $rest = ['where', ['id' => $resource->id]];
            $r = (string) $meth->name;

            $re = call_user_func($first . '\\' . $class . '::' . $rest[0], ...array_slice($rest, 1))->first()->$r->count();
            $counter = $counter + $re;
        }

        return $counter;
    }

    public function profileCompletenes($staff = null)
    {
        if ($staff) {
            $user = $staff;
        } else {
            $user = Auth::user();
        }
        $job_information_max_percent = 15;
        $others_max_percent = 85;

        $additional_details = !empty($user->additional_details) ? $user->additional_details : [];
        $nok_details = !empty($user->next_of_kin) ? Auth::user()->next_of_kin : [];
        
        $job_information = [
            'jobtype_id' => !empty($staff->jobtype_id) ? 1 : 0,
            'date_of_employment' => !empty($staff->date_of_employment) ? 1 : 0,
            'department_id' => !empty($staff->department_id) ? 1 : 0,
            'job_role_id' => !empty($staff->job_role_id) ? 1 : 0,
            'branch_id' => !empty($staff->branch_id) ? 1 : 0,
            'commission_id' => !empty($staff->commission_id) ? 1 : 0
        ];

        $job_information = !empty($job_information) ? array_sum($job_information) / count($job_information) * $job_information_max_percent : 0;

        $others = [
            // 'first_name' => !empty($user->first_name) ? 1 : 0,
            // 'middle_name' => !empty($user->middle_name) ? 1 : 0,
            // 'last_name' => !empty($user->last_name) ? 1 : 0,
            // 'email_address' => !empty($user->email) ? 1 : 0,
            // 'phone_number' => !empty($user->phone) ? 1 : 0,
            // 'alternate_email_address' => !empty($additional_details['alternate_email']) ? 1 : 0,
            // 'username' => !empty($user->username) ? 1 : 0,
            // 'gender' => !empty($user->gender) ? 1 : 0,
            // 'date_of_birth' => !empty($user->date_of_birth) ? 1 : 0,
            // 'marital_status' => !empty($additional_details['marital_status']) ? 1 : 0,
            // 'alternate_number' => !empty($additional_details['alternate_number']) ? 1 : 0,
            // 'nationality' => !empty($additional_details['nationality']) ? 1 : 0,
            // 'state' => !empty($additional_details['state']) ? 1 : 0,
            // 'lga' => !empty($additional_details['lga']) ? 1 : 0,
            // 'residential_address' => !empty($additional_details['residential_address']) ? 1 : 0,
            // 'nok_first_name' => !empty($nok_details['nok_first_name']) ? 1 : 0,
            // 'nok_last_name' => !empty($nok_details['nok_last_name']) ? 1 : 0,
            // 'nok_phone_number' => !empty($nok_details['nok_phone_number']) ? 1 : 0,
            // 'nok_house_address' => !empty($nok_details['nok_house_address']) ? 1 : 0,
            // 'nok_email_address' => !empty($nok_details['nok_email_address']) ? 1 : 0,
            // 'nok_relationship' => !empty($nok_details['nok_relationship']) ? 1 : 0
        ];

        $others = !empty($others) ? array_sum($others) / count($others) * $others_max_percent : 0;
        $total = number_format($job_information + $others);
        
        return $total . '%';
    }

    public function encodeUploadedFile($file)
    {
        return !empty($file) ? url('/') . '/uploads/' . base64_encode($file) : '';
    }

    public function logEmail($data)
    {
        $data['attachments'] = (isset($data['attachments']) && !empty($data['attachments'])) ? json_encode($data['attachments']) : null;
        $mes = new MessageTransactionController();
        $mes->logEmailTransaction($data);

        return;
    }

    public function getLeaveAndAbsencePeriodLeft($staff_id, $type, $internal = false)
    {
        $allowedPeriod = LeaveAndAbsenceSetting::where('company_id', $this->sessionDetails('company_id'))->where('type', $type)->first();
        $allapproved = LeaveAndAbsenceManagement::where(['company_id' => $this->sessionDetails('company_id'), 'type' => $type, 'staff_id' => $staff_id, 'status' => 'approved'])->sum('period');
        // ->count('period');
        if ($allowedPeriod) {
            $left = $allowedPeriod->period - $allapproved;
        } else {
            $left = 0;
        }

        if (!$internal) {
            return response()->json(['message' => 'Retrieved successfully', 'data' => $left], 200);
        }

        return $left;
    }

    public function getAdvanceAndClaimsPeriodLeft($staff_id, $type, $internal = false)
    {
        $allowedPeriod = LeaveAndAbsenceSetting::where('company_id', $this->sessionDetails('company_id'))->where('type', $type)->first();

        $allapproved = AdvanceAndClaims::where(['company_id' => $this->sessionDetails('company_id'), 'type' => $type, 'staff_id' => $staff_id ?? Auth::user()->staff->id, 'status' => 'approved'])->sum('period');


        if ($allowedPeriod) {
            $left = $allowedPeriod->period - $allapproved;
        } else {
            $left = 0;
        }

        if ($internal) {
            return $left;
        }

        return response()->json(['message' => 'Retrieved successfully', 'data' => $left], 200);
    }

    public function cleanJsonInput($input)
    {
        if (isset($input[0]) && ($input[0] == "'" || $input[0] == '"')) {
            $input = substr($input, 1);
        }

        if (isset($input[-1]) && ($input[-1] == "'" || $input[-1] == '"')) {
            $input = substr($input, 0, -1);
        }

        $input = json_decode($input, true);

        return $input;
    }

    public function getFormRequestContractDetails($forms, $modify = false, $reason = '')
    {

        $forms->next_of_kin_information = !empty($forms->next_of_kin_information) ? json_decode($forms->next_of_kin_information, true) : [];

        $means_of_identification = !empty($forms->means_of_identification) ? json_decode($forms->means_of_identification, true) : [];

        $means_of_identification['passport_photo'] = isset($means_of_identification['passport_photo']) && !empty($means_of_identification['passport_photo']) ? $this->encodeUploadedFile($means_of_identification['passport_photo']) : null;
        $means_of_identification['id_card'] = isset($means_of_identification['id_card']) && !empty($means_of_identification['id_card']) ? $this->encodeUploadedFile($means_of_identification['id_card']) : null;
        $means_of_identification['signature'] = isset($means_of_identification['signature']) && !empty($means_of_identification['signature']) ? $this->encodeUploadedFile($means_of_identification['signature']) : null;
        $forms->means_of_identification = $means_of_identification;

        $contract_details = [];

        $contract_ids = (isset($forms->contract_ids) && !empty($forms->contract_ids)) ? json_decode($forms->contract_ids, true) : null;

        if (!empty($contract_ids)) {
            foreach ($contract_ids as $value) {
                $contract = CustomerContract::with('customer', 'property_type_details:type,id,estate_price,settings_and_excavation,infrastructural_fee', 'city:name,id', 'estate:name,id', 'location:name,id', 'building_type:id,name', 'plot_size:id,size', 'plotSize:id,size')->where('customer_id', $forms->customer_id)->where('id', $value)->where('company_id', $this->sessionDetails('company_id'))->select(['id', 'customer_id', 'primary_marketer_id', 'property_type_id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'subscribers', 'contract_type', 'property_type_id', 'form_approval_status','transaction_date','reason', 'created_at'])->first();
                
                if(!empty($contract->form_payment_status) && $contract->form_payment_status == 'paid'){
                    if($modify && $modify == 'approve_contract'){
                        $this->approveCustomerContract($contract);
                    }
                }

                if($modify && $modify == 'decline_contract'){
                    $this->declineCustomerContract($contract, $reason);
                }

                $contract_details[] = [
                    'id' => $contract->id,

                    'customer_id' => $contract->customer_id,
                    'subscribers' => !empty($contract->subscribers) ? json_decode($contract->subscribers) : '',
                    'contract_type' => $contract->contract_type,
                    'property_type' => $contract->property_type_details->type,
                    'property_price' => number_format((float) $contract->property_type_details->estate_price),
                    'city' =>  $contract->city,
                    'building_type' => $contract->building_type,
                    'location' => $contract->location->name,
                    'estate' => $contract->estate,
                    'plot_size' => $contract->plotSize,
                    'infrastructural_fee' => number_format((float)$contract->property_type_details->infrastructural_fee),
                    // 'net_property_fee' => number_format((float) $contract->net_property_fee),
                    // 'discount' => number_format((float) $contract->discount),
                    'transaction_date' => $contract->transaction_date,
                    'primary_marketer' => $contract->primaryMarketer($contract),
                    'settings_and_escavation' => $contract->property_type_details->settings_and_excavation
                ];
            }
        }

        return $contract_details;
    }

    public function approveCustomerContract($contract){
        $contract->update([
            'form_approval_status' => 'approved',
        ]);
    }

    public function declineCustomerContract($contract, $reason)
    {
        $contract->update([
            'form_approval_status' => 'declined',
            'reason' => $reason,
        ]);
    }

    public function generateFormPaymentReceipt($payment)
    {
       
        $name = $this->generateReceiptNumber($payment).'_'. $payment->id;
        $data['receipt_no'] = $name;
        $pathToSave = $this->getPathToSaveFile('receipts/' . $this->sessionDetails('company_slug')) . '/' . $name . '.pdf';

        $pdf = PDF::loadView('receipts.template1', compact('payment', 'data'))->setPaper('a4', 'portrait');

        file_put_contents(base_path() . '/uploads/'. $pathToSave, $pdf->output());

        return [
            'receipt_no' => $name . '.pdf',
            'filename' => $name . '.pdf',
            'filepath' => $pathToSave
        ];
    }

    public function generateReceiptNumber($payment)
    {
        $rand = $payment->id . strtotime($payment->created_at);
        $year = date('Y', strtotime($payment->created_at));
        $month = date('m', strtotime($payment->created_at));

        return $rand . $year . $month;
    }
}
