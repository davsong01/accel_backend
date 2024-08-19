<?php

namespace App\Models;


use App\Traits\CompanyIdTrait;use App\Models\City;
use App\Models\User;
use App\Models\Estate;
use App\Models\Customer;
use App\Models\PlotSize;
use App\Models\Transaction;
use App\Models\BuildingType;
use App\Models\ContractPayment;
use App\Models\SeInfrastructure;
use App\Models\GeneratedLegalDocument;
use Illuminate\Database\Eloquent\Model;
use App\Models\EstatePropertyTypeDetails;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerContract extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'customer_contracts';
    protected $guarded = [];
    protected $appends = ['form_payment_status','contract_name','contract_deal_status', 'contract_history_status'];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function form_transactions()
    {
        return $this->hasMany(Transaction::class, 'transaction_id','form_payment_transaction_id' );
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function contract_payment()
    {
        return $this->hasOne(ContractPayment::class, 'contract_id');
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

    public function getContractNameAttribute(){
        $name = '';
        if($this->property_type == 'building'){
            $building =  $this->property_type_details->building_details->name ?? null;
            $name = $building . ' - ' . $this->estate->name;
        }
        
        if ($this->property_type == 'land') {
            $psize = $this->property_type_details->plot_details->size ?? null;
            $building = $this->property_type_details->building_details->name ?? null;
            $name .= $psize . ' - ';

            if(!empty($building)){
                $name .= $building . ' - ';
            }

            $name .= $this->estate->name;
            
        }

        return $name;
    }

    public function getContractHistoryStatusAttribute()
    {
        $status = NULL;

        if ($this->status == 'lead' || $this->status == 'prospect') {
            $status = $this->status;
        }

        if (in_array($this->contract_status, ['ongoing','closed','completed'])) {
            $status = $this->contract_status;
        }

        return $status;
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

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("Y-m-d", strtotime($value)),
        );
    }

    public function generatedDocument(): HasOne
    {
        return $this->hasOne(GeneratedLegalDocument::class, 'contract_id');
    }

    public function getContractDealStatusAttribute(){
        $deal_status = 'closed';
        if (empty($this->contract_status) || $this->contract_status == 'ongoing') {
            $deal_status = 'open';
        } 
        if(in_array($this->contract_status, ['allocated', 'completed', 'closed'])){
            $deal_status = 'closed';
        }
        return $deal_status;
    }

    public function seInfrastructurePayment(): HasOne
    {
        return $this->hasOne(SeInfrastructure::class, 'transaction_id','sei_payment_transaction_id', );
    }

    public function seInfrastructurePaymentDetails()
    {
        return $this->hasOne(SeInfrastructure::class, 'contract_id');
    }

    public function under_construction(): HasOne
    {
        return $this->hasOne(ContractUnderConstruction::class, 'contract_id');
    }

}
