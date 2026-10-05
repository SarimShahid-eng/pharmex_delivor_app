<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookedOrder extends Model
{
    protected $table = 'booked_orders';
    protected $guarded = [];

    public function booked_order(){
      //  return belongsToMany('App\BookedOrderDetail','order_id');
        return $this->hasMany('App\BookedOrderDetail', 'order_id');
    }
     public function employees(){
        return $this->belongsTo('App\Employees','employee_id','emp_code');
    }

    public function customers(){
        return $this->belongsTo('App\Customers','customer_code','customer_code');
    }
}
