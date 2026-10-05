<?php

namespace App\Exports;

use App\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class OrdersExport implements FromCollection
{
    public function __construct(String $date = null,String $id = null)
    {
         $this->date = $date;
         $this->id = $id;
    }
    public function collection()
    {
        return Orders::select(DB::raw('customers.customer_code, products.product_code, order_details.qty'))
        	->join('customers','customers.id','orders.customer_id')
        	->join('order_details','orders.id','order_details.order_id')
        	->join('products','order_details.item_id','products.id')
        	->where('orders.order_date',$this->date)
            ->where('orders.order_status','booked')
            ->where('orders.employee_id',$this->id)
        	->get();

            
    }
}
