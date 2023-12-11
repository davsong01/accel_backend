<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Customer;
use App\Models\ChartOfAccount;
use App\Models\CustomerContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'payments';

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function contract()
    {
        return $this->belongsTo(CustomerContract::class, 'customer_contract_ids');
    }

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format($value),
        );
    }

}
