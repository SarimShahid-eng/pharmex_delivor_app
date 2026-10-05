<?php

namespace App;

use App\Traits\DianujHashidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderReturn extends Model
{
    use SoftDeletes, DianujHashidsTrait;

    protected $guarded = [];
    protected $table = 'order_return';

    public function Customer()
    {
    	return $this->belongsTo('App\Customers','customer_id');
    }

    public function order_detail()
    {
        return $this->hasMany('App\OrderReturnDetails', 'order_id');
    }
   
}
