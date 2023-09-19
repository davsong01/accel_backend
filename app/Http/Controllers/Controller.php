<?php

namespace App\Http\Controllers;

use App\Models\EstateSettings;
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
   
}

