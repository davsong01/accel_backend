<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvanceAndClaims extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected function proof(): Attribute
    {

        return Attribute::make(
           
            get: fn (mixed $value, array $attributes) => new AdvanceAndClaims(
                $r = $this->getProofs($value, $this),
                $r
            ),
        );
    }

    public function getProofs($value, $all){
        $proofs = [];

        if ($all->type == 'claims') {
            if (!empty($value)) {
                $value = json_decode($value, true);
                foreach ($value as $p) {
                    $proofs[] = app('App\Http\Controllers\Controller')->encodeUploadedFile($p);
                }
            }
        }
        
        return $proofs;
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format((float) $value),
        );
    }

}
