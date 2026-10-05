<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customers;
use App\Groups;
use App\Regions;
use App\Tasks;
use App\Employees;
use App\Products;
use App\Orders;
use App\OrdersDetails;
use App\OrderReturn;
use App\OrderReturnDetails;
use DB;
use App\AndroidVersion;

class APIDataController extends Controller
{
    public function getAllDatta()
    {
        $data = array(
            'regions' => Regions::latest()->get(),
            'employees' => Employees::latest()->get(),
            'customers' => Customers::with(['regions'])->latest()->get(),
            'groups' =>  Groups::with(['Customers'])->latest()->get(),
            'products' => Products::latest()->get(), 
            'tasks' => Tasks::with(['Regions','Groups'])->orderBy('day', 'ASC')->get(),
            
        );
        return response()->json($data);
    }

    public function SyncData(request $request)
    {
        $data = json_decode($request->data);
        foreach ($data as $value) {
             $orders = new Orders();
             $orders->customer_id = $value->customer_id;
             $orders->employee_id = $value->employee_id;
             $orders->order_date = $value->order_date;
             $orders->delivery_date = $value->delivery_date;
             $orders->total_amount = $value->total_amount;
             $orders->status = $value->status;
             $orders->lat = $value->lat;
             $orders->lng = $value->lng;
             $orders->save();
             if ($value->status == 'booked') {
                 foreach ($value->order_details as $order_details) {
                    $details = new OrdersDetails();
                    $details->order_id = $orders->id;
                    $details->item_id = $order_details->item_id;
                    $details->item_price = $order_details->item_price;
                    $details->qty = $order_details->qty;
                    $details->subtotal = $order_details->subtotal;
                    $details->save();
                 }
             }
        }
       $datas = array(
            'status' => 'success',
        );
        return response()->json($datas);
    }

    public function customer_order(request $request)
    {
        $data = json_decode($request->data);
        foreach ($data as $value) {
             $orders = new Orders();
             $orders->customer_id = $value->customer_id;
           //  $orders->employee_id = $value->employee_id;
             $orders->order_date = $value->order_date;
             $orders->delivery_date = $value->delivery_date;
             $orders->total_amount = $value->total_amount;
             $orders->status = $value->status;
             $orders->lat = $value->lat;
             $orders->lng = $value->lng;
             $orders->order_by = $value->order_by;
             $orders->save();
             if ($value->status == 'booked') {
                 foreach ($value->order_details as $order_details) {
                    $details = new OrdersDetails();
                    $details->order_id = $orders->id;
                    $details->item_id = $order_details->item_id;
                    $details->item_price = $order_details->item_price;
                    $details->qty = $order_details->qty;
                    $details->subtotal = $order_details->subtotal;
                    $details->save();
                 }
             }
        }
       $datas = array(
            'status' => 'success',
        );
        return response()->json($datas);
    }
    public function get_my_order(request $request)
    {
        $data = array(
            'status' => 'success',
            'orders' => Orders::where('customer_id',$request->id)->latest()->get(), 
        );
        return response()->json($data);
    }
    public function order_details(request $request)
    {
        $order_data = DB::table('order_details')
            ->join('products', 'order_details.item_id', '=', 'products.id')
            ->where('order_id',$request->id)
            ->get();

        $data = array(
            'status' => 'success',
            'order_details' => $order_data, 
        );
        return response()->json($data);
    }
    public function customer_order_return(request $request)
    {
        $data = json_decode($request->data);
        foreach ($data as $value) {
             $orders = new OrderReturn();
             $orders->customer_id = $value->customer_id;
             $orders->total_amount = $value->total_amount;
             $orders->status = 'pending';
             $orders->save();
                 foreach ($value->order_return_details as $order_details) {
                    $details = new OrderReturnDetails();
                    $details->order_id = $orders->id;
                    $details->item_id = $order_details->item_id;
                    $details->item_price = $order_details->item_price;
                    $details->qty = $order_details->qty;
                    $details->subtotal = $order_details->subtotal;
                    $details->expiry_date = $order_details->expiry_date;
                    $details->save();
                 }
             
        }
       $datas = array(
            'status' => 'success',
        );
        return response()->json($datas);
    }

    public function get_my_order_return(request $request)
    {
        $data = array(
            'status' => 'success',
            'order_retur' => OrderReturn::where('customer_id',$request->id)->latest()->get(), 
        );
        return response()->json($data);
    }
    public function order_return_details(request $request)
    {
        $order_data = DB::table('order_return_details')
            ->join('products', 'order_return_details.item_id', '=', 'products.id')
            ->where('order_id',$request->id)
            ->get();
        $data = array(
            'status' => 'success',
            'order_details' => $order_data, 
        );
        return response()->json($data);
    }

// base auth
    public function get_All_Datta()
    {
        $data = array(
            'regions' => Regions::latest()->get(),
            'employees' => Employees::latest()->get(),
            'customers' => Customers::with(['regions'])->latest()->get(),
            'groups' =>  Groups::with(['Customers'])->latest()->get(),
            'products' => Products::with(['Companies'])->latest()->get(), 
            'tasks' => Tasks::with(['Regions','Groups'])->orderBy('day', 'ASC')->get(),
            
        );
        return response()->json($data);
    }
    
    public function androidVersion(){
        $data = array(
            'version' => AndroidVersion::first(),
        );
        return response()->json($data);
    }
}
