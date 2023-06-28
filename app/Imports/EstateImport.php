<?php

namespace App\Imports;

use App\Models\City;
use App\Models\User;
use App\Models\Estate;
use App\Models\Location;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EstateImport implements ToCollection, WithHeadingRow
{
    private $session;

    function __construct()
    {
        $this->session = app('App\Http\Controllers\Controller');
    }

    public function collection(Collection $rows)
    {
        $company_id = $this->session->sessionDetails('company_id');

        foreach ($rows as $row) {
            Validator::make(
                $row->toArray(),
                [
                    'name' => 'required',
                    'city' => 'required|exists:cities,name',
                    'location' => 'required|exists:locations,name',
                    'description' => 'nullable',
                    'project_supervisor_staff_id' => 'required|exists:users,staff_id',
                    'bypass_approval' => 'required|in:0,1',
                    'property_type' => 'required|in:land,building,both'
                ],
                [
                    'name.required' => 'Undefined name in one or more rows, please check and try again',
                    'city.required' => 'Undefined city in one or more rows, please check and try again',
                    'location.required' => 'Undefined location in one or more rows, please check and try again',
                    'project_supervisor_staff_id.required' => 'Project Supervisor staff Id is invalid in one or more rows',
                    'bypass_approval.required' => 'Bypass Approval is not defined in one or more rows',
                    'property_type.required' => 'Property Typel is not defined in one or more rows'
                ]
            )->validate();

            $city_id = $this->getCityId(trim($row['city']), $company_id);
            $location_id = $this->getLocationId(trim($row['location']), $company_id);
            $project_supervisor = $this->getStaffId(trim($row['project_supervisor_staff_id']), $company_id);

            Estate::updateOrCreate([
                'name' => $row['name'],
                'city_id' => $city_id,
                'location_id' => $location_id,
                'project_supervisor' => $project_supervisor,
                'bypass_approval' => $row['bypass_approval'],
                'description' => $row['description'],
                'property_type' => $row['property_type'],
                'company_id' =>  $company_id
            ]);
        }
    }

    public function getCityId($city, $company_id){
        $cityId = City::where(['name'=>$city,'company_id'=> $company_id])->first();
        if (!$cityId) {
			throw new \Exception('City: '.$city . ' not found!');
        }
        return $cityId->id;
    }

    public function getLocationId($location, $company_id)
    {
        $locationId = Location::where(['name' => $location, 'company_id' => $company_id])->first();
        if (!$locationId) {
			throw new \Exception('Location: ' . $location . ' not found!');
        }
        return $locationId->id;
    }

    public function getStaffId($staff_id, $company_id)
    {
        $staffId = User::where(['staff_id' => $staff_id, 'company_id' => $company_id, 'type'=>'admin'])->first();
        
        if (!$staffId) {
			throw new \Exception('Staff with staff Id of : ' . $staff_id . ' not found!');
        }

        return $staffId->admin->id;
    }
}
