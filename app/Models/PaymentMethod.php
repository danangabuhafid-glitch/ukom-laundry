<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'code', 'is_active', 'is_system', 'settings'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'settings' => 'array',
    ];
}