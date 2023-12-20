<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Staff;
use App\Models\StaffRole;
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
    protected $append = ['additional_details', 'means_of_identification','next_of_kin'];

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
    
    public function getAdditionalDetailsAttribute()
    {
        $additional_details = !empty($this->attributes['additional_details']) ? json_decode($this->attributes['additional_details'], true) : [];
        return $additional_details;
    }

    public function getMeansOfIdentificationAttribute()
    {

        $means_of_identification = !empty($this->attributes['means_of_identification']) ? json_decode($this->attributes['means_of_identification'], true) : [];
        
        $means_of_identification['occupation'] = isset($means_of_identification['occupation']) ? $means_of_identification['occupation'] : null;
        $means_of_identification['place_of_work'] = isset($means_of_identification['place_of_work']) ? $means_of_identification['place_of_work'] : null;
        $means_of_identification['id_card_type'] = isset($means_of_identification['id_card_type']) ? $means_of_identification['id_card_type'] : null;
       
        $means_of_identification['passport_photo'] = isset($means_of_identification['passport_photo']) ? app('App\Http\Controllers\Controller')->encodeGeneralUploadedFile($means_of_identification['passport_photo']) : null;
        
        $means_of_identification['id_card'] = isset($means_of_identification['id_card']) ? app('App\Http\Controllers\Controller')->encodeUploadedFile($means_of_identification['id_card']) : null;
       
        $means_of_identification['signature'] = isset($means_of_identification['signature']) ? app('App\Http\Controllers\Controller')->encodeUploadedFile($means_of_identification['signature']) : null;
        
        return $means_of_identification;
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

}
