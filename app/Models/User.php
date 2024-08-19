<?php

namespace App\Models;

// 

use App\Traits\CompanyIdTrait;use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Lga;
use App\Models\City;
use App\Models\State;
use App\Models\Staff;
use App\Models\Country;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    protected $append = ['next_of_kin'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function getNextOfKinAttribute()
    {
        $next_of_kin = !empty($this->attributes['next_of_kin']) ? json_decode($this->attributes['next_of_kin'], true) : [];

        return $next_of_kin;
    }


    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null) => !empty($value) ? app('App\Http\Controllers\Controller')->encodeGeneralUploadedFile($value) : '',
        );
    }

    protected function getFullnameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name} {$this->middle_name}");
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

}
