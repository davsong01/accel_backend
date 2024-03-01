<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\JobTitle;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class JobTitleImport implements ToModel, SkipsEmptyRows, WithValidation, WithHeadingRow
{
    use Importable;    
    /**
     * @param array $row
     *
     * @return JobTitle|null
     */
    public function model(array $row)
    {
        $row = array_filter($row);

        return new JobTitle([
            'title'          => $row['title'],
            'department_id'  => Department::where('name', $row['department_name'])->firstWhere('company_id', session()->get('company_id', null))->id,
            'company_id' => session()->get('company_id', null)
        ]);
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string'],
            'department_name' => ['required', 'exists:departments,name,company_id,' . session()->get('company_id', null)],
        ];
    }
}
