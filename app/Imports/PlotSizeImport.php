<?php

namespace App\Imports;

use App\Models\PlotSize;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class PlotSizeImport implements ToCollection, WithHeadingRow
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
                    'size' => 'required',
                    Rule::unique('plot_sizes')->where(fn($query) => $query->where('company_id', $company_id)),
                ],
                [
                    'size.required' => 'One or more rows do not have size, please check and try again',
                ]
            )->validate();

            PlotSize::updateOrCreate([
                'size' => $row['size'],
                'company_id' => $company_id
            ]);
        }
    }

    public function headings(): array
    {
        return [
            'size',
        ];
    }
}