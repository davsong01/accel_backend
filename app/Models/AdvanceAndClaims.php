<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvanceAndClaims extends Model
{
    use HasFactory, CompanyIdTrait;
    protected $guarded = [];

    public function staff(){
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    protected function proof(): Attribute
    {

        return Attribute::make(
            get: fn (string $value) => !empty($value) ? app('App\Http\Controllers\Controller')->encodeGeneralUploadedFile($value) : ''
        );
    }

    // public function getProofs($value, $all){
    //     $proofs = [];
        
    //     if ($all->type == 'claims') {
    //         if (!empty($value)) {
    //             $value = json_decode($value, true);
    //             foreach ($value as $p) {
    //                 $proofs[] = app('App\Http\Controllers\Controller')->encodeUploadedFile($p);
    //             }
    //         }
    //     }
        
    // }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format((float) $value),
        );
    }

}
