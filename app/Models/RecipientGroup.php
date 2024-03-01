<?php

namespace App\Models;

use App\Traits\HasManyJsonTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipientGroup extends Model
{
    use HasFactory;
    use HasManyJsonTrait;

    protected $fillable = ['name', 'all_customers', 'location_ids', 'estate_ids', 'city_ids', 'customer_status', 'subscriber_status', 'completed_subscriber_status', 'company_id', 'created_by'];

    protected $casts = [
        'location_ids' => 'array',
        'estate_ids' => 'array',
        'city_ids' => 'array',
        'customer_status' => 'array',
        'subscriber_status' => 'array',
        'completed_subscriber_status' => 'array',
        'all_customers' => 'boolean',
    ];

    protected $with = ['cities', 'company', 'estates', 'locations', ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Staff::class);
    }

    public function locations()
    {
        return $this->hasManyJson(Location::class, 'id', 'location_ids');
    }

    public function estates()
    {
        return $this->hasManyJson(Estate::class, 'id', 'estate_ids');
    }

    public function cities()
    {
        return $this->hasManyJson(City::class, 'id', 'city_ids');
    }
}
