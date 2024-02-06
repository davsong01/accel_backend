<?php

namespace App\Imports;

use App\Models\JobTitle;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class JobTitlesImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return JobTitle|null
     */
    public function model(array $row)
    {
        return new JobTitle([
           'title'     => $row['title'],
           'department_id'    => $row['department_id'],
        ]);
    }
}