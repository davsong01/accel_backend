<?php

namespace App\Http\Controllers;

use App\Models\Estate;
use App\Models\States;
use App\Models\Country;
use App\Models\EstateSettings;
use App\Models\CustomerContract;
use App\Models\Lga;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
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

        return $location.'/'.$name;
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
   
}

