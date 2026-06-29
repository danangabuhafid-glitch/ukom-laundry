<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customer';
    protected $fillable = ['customer_name', 'phone', 'address'];

    public function transOrders()
    {
        return $this->hasMany(TransOrder::class, 'id_customer');
    }

    public function transLaundryPickups()
    {
        return $this->hasMany(TransLaundryPickup::class, 'id_customer');
    }
}
