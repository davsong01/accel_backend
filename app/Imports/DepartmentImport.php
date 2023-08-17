<?php

namespace App\Imports;

use App\Models\Department;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DepartmentImport implements ToCollection, WithHeadingRow
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
                    'description' => 'sometimes',
                ],
                [
                    'name.required' => 'Undefined name in one or more rows, please check and try again',
                ]
            )->validate();

            Department::updateOrCreate([
                'name' => $row['name'],
                'description' => $row['description'],
                'company_id' => $company_id
            ]);
        }
    }
}
