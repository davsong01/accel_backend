<?php

namespace App\Imports;

use App\Models\BuildingType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BuildingTypeImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private $session;

    function __construct()
    {
        $this->session = app('App\Http\Controllers\Controller');
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $company_id = $this->session->sessionDetails('company_id');

        foreach ($rows as $row) {
            Validator::make(
                $row->toArray(),
                [
                    'name' => [
                        'required',
                        Rule::unique('building_types')->where(fn($query) => $query->where('company_id', $company_id)),
                    ],
                ],
                [
                    'name.required' => 'One or more rows do not have name, please check and try again',
                    'name.unique' => "The name '{$row['name']}' already exists for your company.",

                ]
            )->validate();

            BuildingType::create([
                'name' => $row['name'],
                'company_id' => $company_id
            ]);
        }
    }

    public function headings(): array
    {
        return [
            'name',
        ];
    }
}
