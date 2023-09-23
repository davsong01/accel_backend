<?php

namespace App\Http\Controllers;

use App\Models\Estate;
use App\Models\EstateSettings;
use App\Models\CustomerContract;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
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

    public function uploadImage($file, $folder, $width = null, $height = null)
    {
        $imageName = uniqid(9) . '.' . $file->getClientOriginalExtension();
        $imageFile = Image::make($file);

        $imgWidth = $width ?? $imageFile->width();
        $imgHeight = $height ?? $imageFile->height();

        if (!is_null($imgWidth) && !is_null($imgHeight)
        ) {
            if ($imgWidth < $imgHeight) {
                $imageFile->resize($imgWidth, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $imageFile->resize(null, $imgHeight, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }
        
        if (!is_dir($folder)) {
            mkdir($folder, 0700);
        }

        $imageFile->save($folder . '/' . $imageName);
        
        return $imageName;
    }

    public function uploadPrivateFile($file, $location)
    {
        $name = uniqid(9) . '.' . $file->getClientOriginalExtension();

        if (!is_dir($location)) {
            mkdir($location, 0700);
        }

        $file->move($location, $name);

        return $name;
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
   
}

