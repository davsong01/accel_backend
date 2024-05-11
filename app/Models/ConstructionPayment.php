<?php

namespace App\Models;

use App\Traits\HasManyJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ConstructionPayment extends Model
{
    use HasFactory;
    use HasManyJson;

    protected $appends = ['payment_progress'];

    protected $fillable = [
        'company_id',
        'customer_id',
        'contract_id',
        'created_by',
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
        'staff:id,type,status,first_name,last_name,middle_name,title,avatar',
        'customer:id,user_id',
        'customer.user:id,type,status,first_name,last_name,middle_name,title,avatar'
    ];

    protected $casts = [
        'estate_construction_setting_ids' => 'array',
    ];

    public function getPaymentProgressAttribute()
    {
        return round(($this->attributes['amount_paid'] ?? 1) / ($this->attributes['total_amount'] ?? 1) * 100, 2);
    }

    public function payments(){
        return $this->hasMany(ConstructionPaymentThread::class, 'construction_payment_id')->latest();
    }

    public function estate_construction_settings()
    {
        return $this->hasManyJson(EstateConstructionSetting::class, 'id', 'estate_construction_setting_ids');
    }

    public function staff(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function paid_by(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function contract() {
        return $this->belongsTo(CustomerContract::class, 'contract_id');
    }

    public function current_building_phase() {
        return $this->belongsTo(EstateConstructionSetting::class, 'current_building_phase_id');
    }

}
