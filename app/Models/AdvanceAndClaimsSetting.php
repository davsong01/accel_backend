<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceAndClaimsSetting extends Model
{
    use HasFactory, CompanyIdTrait;
    protected $guarded = [];
    protected $table = "advance_and_claims_settings";
}
