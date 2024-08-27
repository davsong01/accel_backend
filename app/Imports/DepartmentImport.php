<?php

namespace App\Imports;

use App\Models\Department;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DepartmentImport implements ToCollection, SkipsEmptyRows, WithHeadingRow
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
                    'name' =>[ 'required',
                    Rule::unique('departments')->where(fn($query) => $query->where('company_id', $company_id)),

                ],
                    'description' => 'sometimes',
                ],
                [
                    'name.required' => 'Undefined name in one or more rows, please check and try again',
                ]
            )->validate();

            Department::create([
                'name' => $row['name'],
                'description' => $row['description'],
                'company_id' => $company_id
            ]);
        }
    }
}
