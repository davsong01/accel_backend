<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\JobTitle;
use Illuminate\Validation\Rule;
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
        $company_id = session()->get('company_id', null);

        return new JobTitle([
            'title'          => $row['title'],
            'department_id'  => Department::where('name', $row['department_name'])->firstWhere('company_id', $company_id)->id,
            'company_id' => $company_id
        ]);
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'unique:job_titles'],
            'department_name' => ['required', 'exists:departments,name,company_id,' . session()->get('company_id', null)],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'department_name.exists' => 'The selected Department Name does not exists.',
            'department_name.required' => 'The Department Name field is required.',
            'title.required' => 'The Title field is required.',
            'title.string' => 'The Title field must be a string.',
        ];
    }
}
