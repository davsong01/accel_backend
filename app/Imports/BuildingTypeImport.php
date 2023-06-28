<?php

namespace App\Imports;

use App\Models\BuildingType;
use Maatwebsite\Excel\Concerns\ToModel;

class BuildingTypeImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new BuildingType([
            //
        ]);
    }
}
