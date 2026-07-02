<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransOrder extends Model
{
    use SoftDeletes;

    protected $table = 'trans_order';
    protected $fillable = ['id_customer', 'order_code', 'order_date', 'order_end_date', 'order_status', 'order_pay', 'order_change', 'total', 'tax_rate', 'tax_amount'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function transOrderDetails()
    {
        return $this->hasMany(TransOrderDetail::class, 'id_order');
    }

    public function transLaundryPickup()
    {
        return $this->hasOne(TransLaundryPickup::class, 'id_order');
    }
}
