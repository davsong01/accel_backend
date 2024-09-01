<?php

namespace App\Imports;

use App\Models\JobRole;
use App\Models\Department;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JobRoleImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
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
                    'name' => [
                        'required',
                        Rule::unique('job_roles')->where(fn($query) => $query->where('company_id', $company_id)),
                    ],
                    'department' => 'required|exists:departments,name,company_id,' . $company_id,
                ],
                [
                    'name.required' => 'Undefined name in one or more rows, please check and try again',
                    'department.required' => 'Undefined department in one or more rows, please check and try again',
                    'department.exists' => 'Invalid department in one or more rows, please check and try again',
                    'name.unique' => "The name '{$row['name']}' already exists for your company.",
                ]
            )->validate();

            $department_id = $this->getDepartmentId(trim($row['department']), $company_id);
           
            JobRole::create([
                'name' => $row['name'],
                'department_id' => $department_id,
                'company_id' =>  $company_id
            ]);
        }
    }

    public function getDepartmentId($department, $company_id){
        $department = Department::where(['name'=>$department,'company_id'=> $company_id])->first();
        
        if (!$department) {
			throw new \Exception('Department: '.$department . ' not found!');
        }
        return $department->id;
    }
}
