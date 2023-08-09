<?php

namespace App\Imports;

use App\Models\BuildingType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BuildingTypeImport implements ToCollection, WithHeadingRow
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
                    'name' => 'required',
                ],
                [
                    'name.required' => 'One or more rows do not have name, please check and try again',
                ]
            )->validate();

            BuildingType::updateOrCreate([
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
