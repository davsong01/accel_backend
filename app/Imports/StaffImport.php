<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Lga;
use App\Models\City;
use App\Models\States;
use App\Models\User;
use App\Models\Staff;
use App\Models\Branch;
use App\Models\Country;
use App\Models\JobRole;
use App\Models\JobType;
use App\Models\Location;
use App\Models\Department;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StaffImport implements ToCollection, WithHeadingRow
{
    private $session;
    private $company_id;

    function __construct()
    {
        $this->session = app('App\Http\Controllers\Controller');
        $this->company_id = $this->session->sessionDetails('company_id');

    }

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
          
            Validator::make(
                $row->toArray(),
                [
                    'email' => [
                        'required', 'email', Rule::unique('users')->where(function ($query) {
                            $query->where('company_id', $this->company_id);
                        })
                    ],
                    'first_name' => 'required',
                    'middle_name' => 'nullable',
                    'last_name' => 'required',
                    'phone_number' => 'required',
                    'gender' => 'required',
                    'branch' => 'nullable',
                    'jobtype' => 'nullable',
                    'username' => 'nullable',
                    'jobrole' => 'nullable',
                    'date_of_employment' => 'nullable',
                    'department' => 'nullable',
                    // 'commission_percentage' => 'nullable',
                    'nationality' => 'nullable',
                    'state' => 'nullable',
                    'lga' => 'nullable',

                    'alternate_number' => 'nullable',
                    'alternate_email' => 'nullable',
                    'residential_address' => 'nullable',
                    'marital_status' => 'nullable',

                    'nok_first_name' => 'nullable',
                    'nok_last_name' => 'nullable',
                    'nok_phone_number' => 'nullable',
                    'nok_house_address' => 'nullable',
                    'nok_email_address' => 'nullable',
                    'nok_relationship' => 'nullable'
                ],
                [
                    'first_name.required' => 'Undefined firstname in one or more rows, please check and try again',
                    'last_name.required' => 'Undefined last name in one or more rows, please check and try again',
                    // 'city.required' => 'Undefined city in one or more rows, please check and try again',
                    // 'location.required' => 'Undefined location in one or more rows, please check and try again',
                    // 'project_supervisor_staff_id.required' => 'Project Supervisor staff Id is invalid in one or more rows',
                    // 'bypass_approval.required' => 'Bypass Approval is not defined in one or more rows',
                    // 'property_type.required' => 'Property Typel is not defined in one or more rows'
                ]
            )->validate();
            $company_id = $this->company_id;
            $branch_id = !empty($row['branch']) ? $this->getBranchId(trim($row['branch']), $company_id) : null;
            $job_type_id = !empty($row['jobtype']) ? $this->getJobTypeId(trim($row['jobtype']), $company_id) : null;
            $job_role_id = !empty($row['jobrole']) ? $this->getJobRoleId(trim($row['jobrole']), $company_id) : null;
            $department_id = !empty($row['department']) ? $this->getDepartmentId(trim($row['department']), $company_id) : null;
            $nationality = !empty($row['nationality']) ? $this->getCountryId(trim($row['nationality']), $company_id) : null;
            $state_id = !empty($row['state']) ? $this->getStateId(trim($row['state']), $company_id) : null;
            $lga = !empty($row['lga']) ? $this->getLgaId(trim($row['lga']), $company_id) : null;
            $date_of_employment = !empty($row['date_of_employment']) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['date_of_employment']))) : null;
            
            $data['additional_details'] = json_encode([
                'alternate_number' => trim($row['alternate_number']) ?? '',
                'alternate_email' => trim($row['alternate_email']) ?? '',
                'residential_address' => trim($row['residential_address']) ?? '',
                'marital_status' => trim($row['marital_status']) ?? '',
                'nationality' => $nationality ?? '',
                'state' => $state_id ?? '',
                'lga' => $lga ?? '',
            ]);

            $data['next_of_kin'] = json_encode([
                'nok_first_name' => trim($row['nok_first_name']) ?? '',
                'nok_last_name' => trim($row['nok_last_name']) ?? '',
                'nok_phone_number' => trim($row['nok_phone_number']) ?? '',
                'nok_house_address' => trim($row['nok_house_address']) ?? '',
                'nok_email_address' => trim($row['nok_email_address']) ?? '',
                'nok_relationship' => trim($row['nok_relationship']) ?? '',
            ]);
           
            try {
                $user = User::create([
                    'email' => $row['email'],
                    'first_name' => $row['first_name'],
                    'type' => 'staff',
                    'middle_name' => $row['middle_name'] ?? '',
                    'last_name' => $row['last_name'] ?? '',
                    'username' => $row['username'] ?? '',
                    'phone_number' => $row['phone_number'] ?? '',
                    'gender' => $row['gender'] ?? '',
                    'additional_details' => $data['additional_details'],
                    'next_of_kin' => $data['next_of_kin'],
                    'branch_id' => $row['branch_id'] ?? '',
                    'date_of_birth' => $row['date_of_birth'] ?? null,
                    'company_id' => $this->company_id,'staff_id' =>  $this->session->generateStaffId($date_of_employment, true),
                ]);
    
                // Create staff
                $staff = Staff::create([
                    'jobrole_id' => $job_type_id ?? '',
                    'date_of_employment' => $date_of_employment ?? '',
                    'user_id' => $user->id ?? '',
                    'department_id' => $department_id ?? '',
                    'jobrole_id' => $job_role_id ?? '',
                    // 'commision_percentage' => $row['commision_percentage'] ?? '',
                    'company_id' => $this->company_id,
                ]);
                //code...
            } catch (\Throwable $th) {
                dd($th->getMessage(), $th->getLine());
                throw new \Exception($th->getMessage());

            }

        }
    }

    public function getCityId($city, $company_id)
    {
        $cityId = City::where(['name' => $city, 'company_id' => $company_id])->first();
        if (!$cityId) {
            throw new \Exception('City: ' . $city . ' not found!');
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

    public function getBranchId($branch, $company_id)
    {
        $branchId = Branch::where(['name' => $branch, 'company_id' => $company_id])->first();
        if (!$branchId) {
            throw new \Exception('Branch: ' . $branch . ' not found!');
        }
        return $branchId->id;
    }

    public function getJobTypeId($job_type, $company_id)
    {
        $jobTypeId = JobType::where(['name' => $job_type, 'company_id' => $company_id])->first();
        if (!$jobTypeId) {
            throw new \Exception('Job Type: ' . $job_type . ' not found!');
        }
        return $jobTypeId->id;
    }

    public function getJobRoleId($jobrole, $company_id)
    {
        $jobRole = JobRole::where(['name' => $jobrole, 'company_id' => $company_id])->first();
        if (!$jobRole) {
            throw new \Exception('Job Role: ' . $jobrole . ' not found!');
        }
        return $jobRole->id;
    }

    public function getDepartmentId($department, $company_id)
    {
        $department = Department::where(['name' => $department, 'company_id' => $company_id])->first();
        if (!$department) {
            throw new \Exception('Department: ' . $department . ' not found!');
        }
        return $department->id;
    }

    public function getCountryId($nationality, $company_id)
    {
        $nationality = Country::where(['name' => $nationality])->first();
        if (!$nationality) {
            throw new \Exception('Country: ' . $nationality . ' not found!');
        }
        return $nationality->id;
    }

    public function getStateId($name, $company_id)
    {
        $state = States::where(['name' => $name])->first();
        
        if (!$state) {
            throw new \Exception('State: ' . $state . ' not found!');
        }
        return $state->id;
    }

    public function getLgaId($lga, $company_id)
    {
        $lga = Lga::where(['name' => $lga])->first();
        
        if (!$lga) {
            throw new \Exception('Country: ' . $lga . ' not found!');
        }
        return $lga->id;
    }
    
}
