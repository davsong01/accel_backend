<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Lga;
use App\Models\User;
use App\Models\Staff;
use ReflectionMethod;
use App\Models\Estate;
use App\Models\States;
use App\Models\Country;
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
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function sessionDetails($needle){
        if(Session::has($needle)){
            return Session::get($needle);
        }else{
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

    public function getContractFormAmount($estate_contracts, $single=null){
        $buildAmount = [];
        $settings = EstateSettings::select(['form_payment_for_land', 'form_payment_for_building'])->where('company_id', $this->sessionDetails('company_id'))->first();
        
        if(isset($single)){
            $buildAmount = 0;
            if ($estate_contracts->property_type == 'building') {
                $buildAmount += $settings->form_payment_for_building;
            }

            if ($estate_contracts->property_type == 'land') {
                $buildAmount += $settings->form_payment_for_land;
            }
           
            return $buildAmount;

        }else{
            foreach($estate_contracts as $contract){
               
                if($contract->property_type == 'building'){
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

    public function generateFormPayment(array $contract_ids, $customer_id){
        $contracts = [];
        $ids = [];
        $estates = [];
       
        foreach($contract_ids as $contract_id){
            // Get estates
            $est = CustomerContract::select('estate_id')->where('id', $contract_id)
            ->where('customer_id', $customer_id)
            ->where('company_id', $this->sessionDetails('company_id'))->value('estate_id');

            if(!empty($est)){
                $estates[]= $est;
                $ids[] = $contract_id;
            }
        }
       
        $estateContracts = [];
        
        foreach($estates as $e){
            // Get the contracts for each estate
            $es = Estate::where('id', $e)->first();
            
            $estateContracts[$e] = [
                'estate_id' => $es->id,
                'form_payment' => $es->form_amount,
                'contracts' => $es->contracts($customer_id)
            ];
        }
        
        $form_payment = [];
       
        foreach($estateContracts as $contract){
           
            $contract['i_form_payment'] = [];
            $s_ids = array_intersect($contract['contracts'], $ids);
            if(array_diff($contract['contracts'], $ids)){
                $dif = (count($contract['contracts']) - count(array_diff($contract['contracts'], $ids)));
                $contract['i_form_payment']['form_amount'] = $dif *  $contract['form_payment'];
            }
            else{
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

    public function generateStaffId($date_of_employment = null, $internal = false){
        $date_of_employment = !empty($date_of_employment) ? Carbon::parse($date_of_employment) : Carbon::today();

        $settings = StaffIdentificationSetting::where('id',$this->sessionDetails('company_id'))->first();
        $lastUser = User::where('company_id', $this->sessionDetails('company_id'))->where('type', 'staff')->latest()->first();
        $lastUserId = $lastUser->id + 1;
        $lastStaffId = $lastUser->staff_id;
       
        if(!$settings) return;

        $id = '';

        if($settings->use_id_generator == 1){
            if($settings->id_generator_type == 'employment_year'){
                $id = $date_of_employment->format('Y'). sprintf('%06d', $lastUserId);
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
                $min = pow(10, $count-1);
                $id = mt_rand($min, $min*9);
            }

        }else{
            $count = strlen($lastStaffId);
            $min = pow(10, $count - 1);
            $id = mt_rand($min, $min * 9);
        }

        if($internal){
            return $id;
        }

        return response()->json(['message' => 'Successful', 'data' => $id], 200);

    }

    public function deleteUploadsFile($imagePath)
    {

        if (file_exists(base_path() . $imagePath)) {
            unlink(base_path() . $imagePath);
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

        $file->storeAs($folder, $name, "uploads");

        return $folder.'/'.$name;
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

        $file->storeAs($folder, $name, "documents");

        return $folder . '/' . $name;
    }

    public function getPathToSaveFile($location = null){
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        
        if($location){
            $location = $location .'/' . $year . '/' . $month . '/' . $day;
        }else{
            $location = $year . '/' . $month . '/' . $day;
        }

        if (file_exists(base_path() . $location)) {
            unlink(base_path() . $location);
        }

        return $location;
    }

    public function deleteFile($file){
        if(Storage::disk('uploads')->exists($file)){
            Storage::disk('uploads')->delete($file);
        }
    }

    public function getStateDetails($state_id){
        $state = collect([
            'id' => null,
            'name' => 'N/A',
        ]);

        $res = States::select('id','name')->where('id',$state_id)->first();
        if($res){
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

    public function notify($data){
        $staff = Staff::where('id', $data['staff_id'])->first();
        $user = $staff->user;
        $data['user_id'] = $user->id;
       
        $user->notify(new StaffNotification($data));
    }

    public static function definedRelations($class, $resource)
    {
        $reflector = new \ReflectionClass('App\Models\\'.$class);
       
        $methods = array_filter($reflector->getMethods(), function($methods) use ($class) {
            return str_contains($methods, $class) && $methods->getModifiers() == ReflectionMethod::IS_PUBLIC;
        });

        $counter = 0;
        foreach($methods as $meth){
            $first = 'App\Models';
            $rest = ['where',['id' =>$resource->id]];
            $r = (string) $meth->name;
            $re = call_user_func($first . '\\' . $class . '::' . $rest[0], ...array_slice($rest, 1))->first()->$r->count();
            $counter = $counter + $re;
        }

        return $counter;
    }

    public function profileCompletenes($staff=null)
    {
        if($staff){
            $user = $staff;
        }else{
            $user = Auth::user();
        }
        
        $additional_details = !empty($user->additional_details) ? json_decode($user->additional_details, true) : [];
        $nok_details = !empty($user->next_of_kin) ? json_decode(Auth::user()->next_of_kin, true) : [];

        $expectedProfile = [
            'first_name' => !empty($user->first_name) ? 1 : 0,
            'middle_name' => !empty($user->middle_name) ? 1 : 0,
            'last_name' => !empty($user->last_name) ? 1 : 0,
            'email_address' => !empty($user->email) ? 1 : 0,
            'phone_number' => !empty($user->phone) ? 1 : 0,
            'alternate_email_address' => !empty($additional_details['alternate_email']) ? 1 : 0,
            'username' => !empty($user->username) ? 1 : 0,
            'gender' => !empty($user->gender) ? 1 : 0,
            'date_of_birth' => !empty($user->date_of_birth) ? 1 : 0,
            'marital_status' => !empty($additional_details['marital_status']) ? 1 : 0,
            'alternate_number' => !empty($additional_details['alternate_number']) ? 1 : 0,
            'nationality' => !empty($additional_details['nationality']) ? 1 : 0,
            'state' => !empty($additional_details['state']) ? 1 : 0,
            'lga' => !empty($additional_details['lga']) ? 1 : 0,
            'residential_address' => !empty($additional_details['residential_address']) ? 1 : 0,
            'nok_first_name' => !empty($nok_details['nok_first_name']) ? 1 : 0,
            'nok_last_name' => !empty($nok_details['nok_last_name']) ? 1 : 0,
            'nok_phone_number' => !empty($nok_details['nok_phone_number']) ? 1 : 0,
            'nok_house_address' => !empty($nok_details['nok_house_address']) ? 1 : 0,
            'nok_email_address' => !empty($nok_details['nok_email_address']) ? 1 : 0,
            'nok_relationship' => !empty($nok_details['nok_relationship']) ? 1 : 0
        ];
        
        return number_format(array_sum($expectedProfile) / count($expectedProfile) * 100) . '%';
    }

    public function encodeUploadedFile($file){
        return !empty($file) ? url('/') . '/uploads/' . base64_encode($file) : '';
    }

    public function logEmail($data){
        $mes = new MessageTransactionController();
        $mes->logEmailTransaction($data);

        return;
    }
   
    public function getLeaveAndAbsencePeriodLeft($staff_id, $type, $internal=false){
        $allowedPeriod = LeaveAndAbsenceSetting::where('company_id', $this->sessionDetails('company_id'))->where('type', $type)->first();
        $allapproved = LeaveAndAbsenceManagement::where(['company_id' => $this->sessionDetails('company_id'), 'type' => $type, 'staff_id' => $staff_id, 'status' => 'approved'])->sum('period');
        // ->count('period');
        if ($allowedPeriod) {
            $left = $allowedPeriod->period - $allapproved;
        } else {
            $left = 0;
        }
        
        if(!$internal){
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
        dd($type);
        if ($internal) {
            return $left;
        }

        return response()->json(['message' => 'Retrieved successfully', 'data' => $left], 200);
    }
}

