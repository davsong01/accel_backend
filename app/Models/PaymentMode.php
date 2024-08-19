<?php

namespace App\Models;



use App\Traits\CompanyIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMode extends Model
{
    use HasFactory, CompanyIdTrait;

    protected $fillable = ['mode', 'company_id'];

    protected $table = 'payment_modes';

    public function payment_mode() : HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
