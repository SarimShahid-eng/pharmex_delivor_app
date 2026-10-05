<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\BookedOrder;
use App\BookedOrderDetail;
use App\Products;
use App\BookedOrderTableLogs;
use App\Customers;
use App\Employees;

class Imports extends AdminController
{

    public static function csvToArray(Request $req) {
        
        $req->validate([
            'file_name' => 'required|mimes:txt,TXT',
        ]);
        //get the file name and file extension
        $file =  $req->file('file_name')->getClientOriginalName();
        // $extension = $req->file('file_name')->getClientOriginalExtension();
        
        $filename = $req->file('file_name');

        if (!file_exists($filename) || !is_readable($filename)){
            return false;
        }
        
        //if file is already uploaded in database then redirect otherwise add
        if( BookedOrderTableLogs::where('file_name',$file)->first() ){
            return redirect()->back()->with('msg','File is already uploaded');
        }

        set_time_limit(0);

        $header = ['invoice_no', 'date', 'customer_code', 'employee_id', 'product_code', 'batch', 'expiry_date', 'rate', 'qty', 'bonus', 'total', 'status'];
        $csv_array = collect(\App\Helpers\CommonHelpers::order_records_csv($filename, ',', $header));


        $customer_codes = $csv_array->pluck('customer_code');//->forPage($start, $limit);
        $customers = Customers::whereIn('customer_code', $customer_codes)->get(['id', 'customer_code'])->pluck('customer_code');
        $diff_customers = $customer_codes->diff($customers);

        $product_codes = $csv_array->pluck('product_code');//->forPage($start, $limit);
        $products = Products::whereIn('product_code', $product_codes)->get(['id', 'product_code'])->pluck('product_code');
        $diff_products = $product_codes->diff($products);

        $all_data = $csv_array->whereIn('customer_code', $customers)->whereIn('product_code', $products);

    //    $all_data = $csv_array;

        $sum_values = $booked_data = $booked_details_data = [];
        $date = date('Y-m-d H:i:s');

        foreach($all_data as $row){
            $sum_values[$row['invoice_no']] = isset($sum_values[$row['invoice_no']]) ?  $sum_values[$row['invoice_no']] + $row['total'] : $row['total'];
        }

        $all_invoices = $all_data->pluck('invoice_no');
        $old_data = BookedOrder::whereIn('invoice_number', $all_invoices)->get(['id', 'invoice_number'])->pluck('invoice_number');
        $_all_data = $all_data->whereNotIn('invoice_no', $old_data);

        foreach($_all_data as $row){
            if(!in_array($row['invoice_no'], array_column($booked_data, 'invoice_number'))){
                $booked_data[] = array(
                    'invoice_number' => $row['invoice_no'],
                    'customer_code' => $row['customer_code'],
                    'employee_id' => $row['employee_id'],
                    'total_amount' => $sum_values[$row['invoice_no']] ?? 0,
                    'status' => $row['status'],
                    'order_process_date' => date('Y-m-d', strtotime($row['date'])),
                    'created_at' => $date,
                    'updated_at' => $date,
                );
            }
        }
        
        BookedOrder::insert($booked_data);

        $chunk = 1000;

        $_all_data_count = (int) ceil($_all_data->count() / $chunk);
        for($i = 0; $i < $_all_data_count; $i++){
            $booked_details_data = [];
            $data = $_all_data->forPage($i, $chunk);
            foreach($data as $row){
                $booked_details_data[] = array(
                    'invoice_number' => $row['invoice_no'],
                    'product_code' => $row['product_code'],
                    'rate' => $row['rate'],
                    'qty' => $row['qty'],
                    'bonus' => $row['bonus'],
                    'subtotal' => $row['total'],
                    'batch_code' => $row['batch'],
                    'expiry_date' => date('Y-m-d', strtotime($row['expiry_date'])),
                    'created_at' => $date,
                    'updated_at' => $date,
                );
            }
            if(count($booked_details_data) > 0){
                \DB::table('booked_order_details')->insert($booked_details_data);
                usleep(100);
            }
        }

        $arr = array(
            'title' => 'File Import/Export',
            'emp_data' => Employees::where('is_active',1)->latest()->get(),
        );
        $arr['products'] = $diff_products;
        $arr['customers'] = $diff_customers;
        $arr['msg'] = 'File uploaded successfully';

       $logs = new BookedOrderTableLogs();
       $logs->file_name = $file;
       $logs->save();
//dd($diff_customers);
        return redirect()->route('admin.files')->with('success',array('msg' => 'File uploaded successfully','customers' => $diff_customers,'products' => $diff_products));
      //  return view('admin.orders.files')->with($arr);
    }
    
    // public static function csvToArray(Request $req, $delimiter = ',') {
    //     $req->validate([
    //         'file_name' => 'required|mimes:txt,TXT',
    //     ]);
    //     //get the file name and file extension
    //     $file =  $req->file('file_name')->getClientOriginalName();
    //     $extension = $req->file('file_name')->getClientOriginalExtension();
    //     //validating file extension
    //     // echo $extension;
    //     // die();
    //     // if($extension !== 'txt' || $extension !== 'TXT'){
    //     //     return redirect()->back()->with('msg','Please upload the text file');
    //     // }
        
    //     //if file is already uploaded in database then redirect otherwise add
    //     if( BookedOrderTableLogs::where('file_name',$file)->first() ){
    //         return redirect()->back()->with('msg','File is already uploaded');
    //     }else{
    //         $logs = new BookedOrderTableLogs;
    //         $logs->file_name = $file;
    //         $logs->save();
    //     }
        
    //     $filename = $req->file('file_name');
    //     $header = null;
        
    //     if (!file_exists($filename) || !is_readable($filename))
    //         return false;

    //     if (($handle = fopen($filename, 'r')) !== false) {
    //         while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) { 
   
    //             $data[] = $row; 
                    
    //         }
    //         fclose($handle);
    //     }else{
    //         return redirect()->back()->with('msg','Could not read this file');
    //     }
        
    //     $arr = array(
    //         'title' => 'File Import'
    //     );
    //     $arr['products'] = array();
    //     $arr['employee'] = array();
    //     $arr['customers'] = array();
 
    //     foreach($data AS $key=>$csv){
    //         foreach($data[$key] AS $value){
    //             $explode = explode('#',$value);
    //             if($explode[22] == 'S' || $explode[22] == 's'){
    //                 if( $employee = Employees::find($explode[6]) ){
    //                     if( $customer = Customers::where('customer_code',$explode[4])->first() ){
    //                         if( $product = Products::where('product_code',$explode[8])->first() ){
    //                             //if there is no recrod exists then add a new record otherwise update 
    //                             if( !$booked_order = BookedOrder::where('invoice_number',$explode[0])->first() ){
    //                                 //echo "booked order run<br>";
    //                                 $booked_order = new BookedOrder;
    //                             }
    //                             //if id is set than update otherwise add new record
    //                             if($booked_order->id){
    //                                     $booked_order->invoice_number = $explode[0];
    //                                     //$booked_order->order_process_date = date('Y-m-d',strtotime($explode[2]));
    //                                     //$booked_order->customer_code = $explode[4];
    //                                     $booked_order->employee_id = $explode[6];
    //                                     $booked_order->product_code = $explode[8];
    //                                     //$booked_order->batch = $explode[10];
    //                                     //$booked_order->exipry_date = date('Y-m-d',strtotime($explode[12]));
    //                                     //$booked_order->rate += $explode[14];
    //                                     //$booked_order->quantity += $explode[16];
    //                                     //$booked_order->bonus += $explode[18];
    //                                     $booked_order->total_amount += $explode[20];
    //                                     $booked_order->status = $explode[22];
    //                                     $booked_order->created_at = date('Y-m-d H:i:s');
    //                                     $booked_order->updated_at = date('Y-m-d H:i:s');
    //                                     $booked_order->save();
    //                                     //echo "found run <br>";
    //                             }else{
    //                                     //$booked_order = new BookedOrder;
    //                                     $booked_order->invoice_number = $explode[0];
    //                                     //$booked_order->order_process_date = date('Y-m-d',strtotime($explode[2]));
    //                                     //$booked_order->customer_code = $explode[4];
    //                                     $booked_order->employee_id = $explode[6];
    //                                     $booked_order->product_code = $explode[8];
    //                                     //$booked_order->batch = $explode[10];
    //                                     //$booked_order->exipry_date = date('Y-m-d',strtotime($explode[12]));
    //                                     //$booked_order->rate = $explode[14];
    //                                     //$booked_order->quantity = $explode[16];
    //                                     //$booked_order->bonus = $explode[18];
    //                                     $booked_order->total_amount = $explode[20];
    //                                     $booked_order->status = $explode[22];
    //                                     $booked_order->created_at = date('Y-m-d H:i:s');
    //                                     $booked_order->updated_at = date('Y-m-d H:i:s');
    //                                     $booked_order->save();
    //                                     //echo "not found run <br>";
    //                             }
    //                             $booked_order_detail = new BookedOrderDetail;
    //                             $booked_order_detail->order_id = $booked_order->id;
    //                             $booked_order_detail->product_id = $product->id;
    //                             $booked_order_detail->employee_id = $employee->id;
    //                             $booked_order_detail->customer_id = $customer->id;
    //                             $booked_order_detail->rate = $explode[14];
    //                             $booked_order_detail->quantity = $explode[16];
    //                             $booked_order_detail->subtotal = $explode[20];
    //                             $booked_order_detail->batch_code = $explode[10];
    //                             $booked_order_detail->order_process_date = date('Y-m-d',strtotime($explode[2]));
    //                             $booked_order_detail->expiry_date = date('Y-m-d',strtotime($explode[12]));
    //                             $booked_order_detail->save();
    //                         }else{
    //                             //echo "product not found <br>";
                                
    //                             if(!in_array($explode[8],$arr['products'])){
    //                                 $arr['products'][] = $explode[8];
    //                             }
    //                         }//end product if
    //                     }else{
    //                         if(!in_array($explode[4],$arr['customers'])){
    //                             $arr['customers'][] = $explode[4];
    //                         }
    //                     }//end customers if    
    //                 }else{
    //                     //echo "employee not found <br>";
    //                     if(!in_array($explode[6],$arr['employee'])){
    //                         $arr['employee'][] = $explode[6];
    //                     }
                        
    //                 }//end employee if
    //             }
    //         }
            
    //     } 
    //      return view('admin.orders.files')->with($arr);
    // }
}