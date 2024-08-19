<?php

namespace App\Models;


use App\Traits\CompanyIdTrait;use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractUnderConstructionThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'created_by_id',
        'construction_payment_id',
        'chart_of_account_id',
        'payment_slip',
        'description',
        'amount_paid',
        'balance_before',
        'balance_after',
        'estate_construction_setting_ids'
    ];

    protected $casts = [
        'estate_construction_setting_ids' => 'array',
    ];


    public function estate_construction_settings()
    {
        return $this->hasManyJson(EstateConstructionSetting::class, 'id', 'estate_construction_setting_ids');
    }

    public function created_by(){
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
