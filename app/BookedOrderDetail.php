<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookedOrderDetail extends Model
{
    protected $table = 'booked_order_details';
    protected $guarded = [];

    public function products(){
        return $this->belongsto('App\Products', 'product_code','product_code');
    }
   

    public function booked_order(){
          return $this->belongsto('App\BookedOrder', 'invoice_number','invoice_number');
      }
   
}
