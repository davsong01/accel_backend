<?php

namespace App\Models;

use App\Models\City;
use App\Models\User;
use App\Models\Estate;
use App\Models\Customer;
use App\Models\PlotSize;
use App\Models\Transaction;
use App\Models\BuildingType;
use App\Models\GeneratedLegalDocument;
use Illuminate\Database\Eloquent\Model;
use App\Models\EstatePropertyTypeDetails;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomerContract extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'customer_contracts';
    protected $guarded = [];
    protected $appends = ['form_payment_status','contract_name'];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function building_type()
    {
        return $this->belongsTo(BuildingType::class, 'building_type');
    }

    public function plot_size()
    {
        return $this->belongsTo(PlotSize::class, 'plot_size');
    }

    public function plotSize()
    {
        return $this->belongsTo(PlotSize::class, 'plot_size');
    }

    public function building_type_details()
    {
        return $this->belongsTo(BuildingType::class, 'building_type');
    }
 

    public function plot_size_details()
    {
        return $this->belongsTo(PlotSize::class, 'plot_size');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function property_type_details()
    {
        return $this->belongsTo(EstatePropertyTypeDetails::class, 'property_type_id');
    }

    public function getFormPaymentStatusAttribute()
    {
        $payment = Transaction::where('transaction_id', $this->form_payment_transaction_id)->first();
        
        if($payment){
            return 'paid';
        }else{
            return 'unpaid';
        }
       
    }

    // public function getFormAmountPaidAttribute()
    // {
    //     $payment = Transaction::where('transaction_id', $this->transaction_id)->where('customer_id',$this->customer_id)->where('purpose','form_payment')->first();
    //     dd($payment, $this->id);
    //     if ($payment) {
    //         return number_format((float) $payment->amount);
    //     } else {
    //         return 0;
    //     }
    // }

    public function getContractNameAttribute(){
        $name = '';
       
        if($this->property_type == 'building'){
            if(isset($this->building_type_details->name) && isset($this->estate->name)){
                $name = $this->building_type_details->name . ' - ' . $this->estate->name;
            }
        }

        if ($this->property_type == 'land') {
            if(isset($this->plot_size_details->size) && isset($this->building_type_details->name) && isset($this->estate->name)){
                $name = $this->plot_size_details->size . ' - ' . $this->building_type_details->name . ' - ' . $this->estate->name;
            }
        }
        
        return $name;

    }

    public function primaryMarketer()
    {
        if(!empty($this->primary_marketer_id)){
            $staff = Staff::with('user:first_name,middle_name,last_name,id')->where('id', $this->primary_marketer_id)->first();
            
            $details = [
                'staff_id' => $staff->id ?? null,
                'first_name' => $staff->user->first_name ?? null,
                'middle_name' => $staff->user->middle_name ?? null,
                'last_name' => $staff->user->last_name ?? null,
            ];
            
            return $details;

        }else{
            return null;
        }
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("Y-m-d", strtotime($value)),
        );
    }

    public function generatedDocument(): HasOne
    {
        return $this->hasOne(GeneratedLegalDocument::class, 'contract_id');
    }
}
