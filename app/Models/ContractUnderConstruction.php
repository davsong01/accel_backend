<?php

namespace App\Models;

use App\Traits\HasManyJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ContractUnderConstruction extends Model
{
    use HasFactory;
    use HasManyJson;

    protected $appends = ['payment_progress'];

    protected $fillable = [
        'company_id',
        'customer_id',
        'contract_id',
        'created_by_id',
        'estate_construction_setting_ids',
        'amount_paid',
        'total_amount',
        'current_building_phase_id', 
        'chart_of_account_id',
    ];

    protected $with = [
        'payments',
        'contract',
        'estate_construction_settings',
        'created_by:id,type,status,first_name,last_name,middle_name,title,avatar',
        'customer:id,user_id',
        'customer.user:id,type,status,first_name,last_name,middle_name,title,avatar,staff_id,customerId',
        'customer.user.staff:id,user_id',
        'current_building_phase',
        'chart_of_account'
    ];

    protected $casts = [
        'estate_construction_setting_ids' => 'array',
    ];

    public function getPaymentProgressAttribute()
    {
        $t_amt = $this->attributes['total_amount'] ?? 1;
        $p_amt = $this->attributes['amount_paid'] ?? 1;

        $perc = ($p_amt / $t_amt) * 100;
        return max(0, min(100, floatval(substr(strval($perc), 0, 5))));
    }

    public function payments(){
        return $this->hasMany(ContractUnderConstructionThread::class, 'contract_under_construction_id')->latest();
    }

    public function estate_construction_settings()
    {
        return $this->hasManyJson(EstateConstructionSetting::class, 'id', 'estate_construction_setting_ids');
    }

    public function created_by(){
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function paid_by(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function contract(){
        return $this->belongsTo(CustomerContract::class, 'contract_id');
    }

    public function current_building_phase() {
        return $this->belongsTo(EstateConstructionSetting::class, 'current_building_phase_id');
    }
    
    public function chart_of_account() {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

}
