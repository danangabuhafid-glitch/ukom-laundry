<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'name', 'discount_type', 'discount_value', 
        'min_qty', 'start_date', 'end_date', 'is_active'
    ];

    public function services()
    {
        return $this->belongsToMany(TypeOfService::class, 'promo_services', 'promo_id', 'type_of_service_id');
    }
}
