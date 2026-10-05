<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customers;
use Illuminate\Support\Facades\DB;


class NotificationController extends Controller
{
    public function all_notification(Request $request){
        
        
        if (!isset($request->search_customer)){
            
            $result = DB::table('order_return')
                        ->join('customers', 'order_return.customer_id', '=', 'customers.id')
                        ->join('town', 'customers.town_id','=', 'town.id')
                        ->join('regions', 'customers.region_id','=', 'regions.id')
                        ->select('order_return.*','customers.customer_name','town.town','regions.region_name')
                        ->where('order_return.status','=','pending')
                        ->orderby('order_return.created_at','ASC')
                        ->paginate(10);
            
            $data = array(
                'title' => "All Notifications",
                'customers' => Customers::get(),
                'Records' => $result,
            );
            
            return view('admin.notification.all_notification')->with($data);
            
        }else{
            
            
            $search_data = hashids_decode($request->search_customer);
            $start_date = date('Y/m/d', strtotime($request->date_from));
            $end_date = date('Y/m/d', strtotime($request->date_to));
            
            if(isset($search_data) && !isset($request->date_from) && !isset($request->date_to)){
                
                $result = DB::table('order_return')
                        ->join('customers', 'order_return.customer_id', '=', 'customers.id')
                        ->join('town', 'customers.town_id', '=', 'town.id')
                        ->join('regions', 'customers.region_id','=', 'regions.id')
                        ->select('order_return.*', 'customers.customer_name','town.town','regions.region_name')
                        ->where('order_return.status','=','pending')
                        ->Where('customers.id','=',$search_data)
                        ->orderby('order_return.created_at','ASC')
                        ->paginate(10);
                
                $data = array(
                    'title' => "All Notifications",
                    'customer_id'=>$search_data, 
                    'Records' => $result,
                    'customers' => Customers::get(),
                );
                //dd($data['customer_id']);
                    return view('admin.notification.all_notification')->with($data);
                
            }else{
                
                $result = DB::table('order_return')
                        ->join('customers', 'order_return.customer_id', '=', 'customers.id')
                        ->join('town', 'customers.town_id', '=', 'town.id')
                        ->join('regions', 'customers.region_id','=', 'regions.id')
                        ->select('order_return.*', 'customers.customer_name','town.town','regions.region_name')
                        ->where('order_return.status','=','pending')
                        ->Where('customers.id','like','%'.$search_data.'%')
                        ->whereBetween('order_return.created_at', [$start_date,$end_date])
                        ->orderby('order_return.created_at','ASC')
                        ->paginate(10);
                  
                $data = array(
                    'title' => "All Notifications",
                    'Records' => $result,
                    'customer_id'=>$search_data,
                    'customers' => Customers::get(),
                );  
                    return view('admin.notification.all_notification')->with($data);  
                
            }
            
            
            
        }
    }
    
}
