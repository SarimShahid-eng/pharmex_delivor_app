<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    use SoftDeletes, DianujHashidsTrait;

    protected $guarded = [];
    protected $table = 'orders';

    public function order_detail()
    {
        return $this->hasMany('App\OrdersDetails', 'order_id');
    }
    public function Customer()
    {
    	return $this->belongsTo('App\Customers','customer_id');
    }
    public function Employee()
    {
    	return $this->belongsTo('App\Employees','employee_id');
    }
    // public function town(){
    //     return $this->belongsTo('App\Town','town_id');
    // }


}
