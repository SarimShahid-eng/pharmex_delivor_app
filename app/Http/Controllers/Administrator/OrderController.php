<?php
namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;
use Response;
use File;
use App\Orders;
use App\OrderReturn;
use App\OrdersDetails;
use App\OrderReturnDetails;
use CommonHelpers;
use App\BookedOrder;
use App\BookedOrderDetail;
use DB;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Employees;
use App\Customers;
use App\Regions;
use App\Stock;
use App\Companies;
use App\Products;
use App\VendorClaimOrder;
use App\VendorClaimOrderDetails;


class OrderController extends AdminController
{
    public function index(Request $request)
    {   
    //     DB::statement(DB::raw('set @rownum=0'));  
    // echo    Regions::select(DB::raw('orders.*,customers.customer_name,employee.employee_name,regions.region_name,town.town, @rownum  := @rownum  + 1 AS rownum'))
    //                     ->join('customers','regions.id','customers.region_id')
    //                     ->join('orders','orders.customer_id','customers.id')
    //                     ->join('employee','employee.id','orders.employee_id')
    //                     ->join('town','town.id','customers.town_id')
    //                     ->where('orders.order_status','booked')
    //                     ->where('regions.id',2)
    //                     ->orderBy('orders.id')
    //                     //->groupBy('regions.id')
    //                     ->get();

    //                     die()
    
        if (!CommonHelpers::rights('orders_view')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
    	$data = array(
            'title' => 'All Orders',
            'booked_order' =>  Orders::where('order_status','booked')->count(),
            'processed_order' =>  Orders::where('order_status','processed')->count(),
            'completed_order' =>  Orders::where('order_status','completed')->count(),
            'cancel_order' =>  Orders::where('order_status','cancel')->count(),
            'booked_order_data' =>  Orders::where('order_status','booked')->limit(5)->get(),
            'processed_order_data' =>  Orders::where('order_status','processed')->limit(5)->get(),
            'completed_order_data' =>  Orders::where('order_status','completed')->limit(5)->get(),
            'cancel_order_data' =>  Orders::where('order_status','cancel')->limit(5)->get(),
        );
        return view('admin.orders.all_orders')->with($data); 
    }    

    public function list(Request $request)
    {  
        
        DB::statement(DB::raw('set @rownum=0'));  
        $locations = Regions::select(DB::raw('orders.*,customers.customer_name,employee.employee_name,regions.region_name,town.town, @rownum  := @rownum  + 1 AS rownum'))
                    ->join('customers','regions.id','customers.region_id')
                    ->join('orders','orders.customer_id','customers.id')
                    ->join('employee','employee.id','orders.employee_id')
                    ->join('town','town.id','customers.town_id')
                    ->where('orders.order_status',strtolower($request->status));
        $start_date = date('Y/m/d', strtotime($request->date_from));
        $end_date = date('Y/m/d', strtotime($request->date_to));                
        if($request->user_type == 'rout'){
            $rout_id = hashids_decode($request->user_id);
            $locations = $locations->where('regions.id',$rout_id);
        }if ($request->user_type == 'employee') { 
            $locations = $locations->where('orders.employee_id',hashids_decode($request->user_id));
        }if($request->user_type == 'customer'){
            $locations = $locations->where('orders.customer_id',hashids_decode($request->user_id));
        }             
        if ($request->isMethod('post')){
            $locations = $locations->orderBy('orders.id','desc')->whereBetween('orders.created_at', [$start_date,$end_date])->get();
        }else{
            $locations = $locations->orderBy('orders.id','desc')->limit(100) ->get();
        }

       
         return datatables()->of($locations)
                    ->addColumn('order_date', function($locations){
                        return \CommonHelpers::date_format_custom($locations->order_date);
                    })
                    ->addColumn('order_time', function($locations){
                            return $locations->created_at->format('h:i A'); 
                    })
                    ->addColumn('delivery_date', function($locations){
                        return \CommonHelpers::date_format_custom($locations->delivery_date);
                    })
                    ->addColumn('employee_name', function($locations){
                        $employee_name = '-';
                        if ($locations->employee_name !=null) {
                        	$employee_name = $locations->employee_name;
                        }
                        return $employee_name;
                    
                    })
                    // ->addColumn('customer_name', function($locations){
                    //     $customer_name = '-';
                    //     if ($locations->customer !=null) {
                    //     	$customer_name = $locations->customer->customer_name;
                    //     }
                    //     return $customer_name;
                    // })
                    ->addColumn('action', function($locations){
                        $button = '';
                        $button = '<a type="button" name="edit" href="#" class="btn btn-outline-primary btn-rounded waves-effect waves-light model_btn" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="'.$locations->hashid.'"><i class="fas fa-eye"></i></a>';
                    if(CommonHelpers::rights('orders_update')){    
                        $button .= '&nbsp;&nbsp;&nbsp;<a type="button" name="edit" href="'.route('admin.order.update', $locations->hashid).'" class="btn btn-outline-primary btn-rounded waves-effect waves-light model_btn"><i class="fas fa-pencil-alt"></i></a>';
                    }    
                        return $button; 
                    })
                    ->rawColumns(['action','employee_name','order_date','order_time','delivery_date'])
            ->make(true);
            //  $data = array(
            //     'title' => $request->title,
            //     'user_id' => $request->user_id, 
            //     'user_type' => $request->user_type,
            //     'request'  => $request,
            //         'locations' => $locations
            // );
            // return view('admin.orders.all_orders_view')->with($data);
            
    }
    public function order_details(Request $request)
    {   //dd(hashids_decode($request->id));
    	$details = OrdersDetails::with(['products'])->where('order_id',hashids_decode($request->id))->get();
    	echo json_encode(array('items' => $details));
    }
    public function order_update(Request $request)
    {
        if (!CommonHelpers::rights('orders_update')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
    	$data = array(
            'title' => 'Oder Update',
            'orders' => Orders::hashidOrFail($request->id),
        );
        return view('admin.orders.order_update')->with($data);
    }
    public function status_change(Request $request)
    {
    	//dd($request);
    	DB::table('orders')->where('id', hashids_decode($request->order_id))->update(['order_status' => $request->status]);
    	return response()->json([
            'success' => 'Order status change Successfully',
            'redirect' => route('admin.orders'),
        ]);
    }

    public function order_return()
    {
        if (!CommonHelpers::rights('orders_return_view')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
    	 $data = array(
            'title' => 'All Orders Return',
        );
        return view('admin.orders.all_order_return')->with($data); 
    }
    public function orders_return_list()
    {
    	DB::statement(DB::raw('set @rownum=0'));   
        $locations = OrderReturn::with(['Customer'])->latest()->get(['order_return.*',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return datatables()->of($locations)
                    ->addColumn('order_date', function($locations){
                        return \CommonHelpers::date_format_custom($locations->created_at);
                    })
                    ->addColumn('approval_date', function($locations){
                    	if ($locations->approval_date != null) {
                    		return \CommonHelpers::date_format_custom($locations->approval_date);
                    	}else{
                    		return '-';
                    	}
                        
                    })
                    ->addColumn('action', function($locations){
                        $button = '<a type="button" name="edit" href="#" class="btn btn-outline-primary btn-rounded waves-effect waves-light model_btn" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="'.$locations->hashid.'"><i class="fas fa-eye"></i></a>';
                    if(CommonHelpers::rights('orders_return_update')){    
                        $button .= '&nbsp;&nbsp;&nbsp;<a type="button" name="edit" href="'.route('admin.order_return.update', $locations->hashid).'" class="btn btn-outline-primary btn-rounded waves-effect waves-light model_btn"><i class="fas fa-pencil-alt"></i></a>';
                    }    
                        return $button; 
                    })
                    ->rawColumns(['action','order_date','approval_date'])
            ->make(true);
    }
    public function order_retuen_details(Request $request)
    {
    	$details = OrderReturnDetails::with(['products'])->where('order_id',hashids_decode($request->id))->get();
    	echo json_encode(array('items' => $details));
    }

    public function order_return_update(Request $request)
    {
        if (!CommonHelpers::rights('orders_return_update')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
    	$data = array(
            'title' => 'Oder Update',
            'orders' => OrderReturn::hashidOrFail($request->id),
        );
        return view('admin.orders.order_return_update')->with($data);
    }

    public function order_return_status_change(Request $request)
    {
    	//dd($request);
    	DB::table('order_return')->where('id', hashids_decode($request->order_id))->update(['status' => $request->status,'approval_date' => date('Y-m-d')]);
    	return response()->json([
            'success' => 'Order status change Successfully',
            'redirect' => route('admin.orders.return'),
        ]);
    }
    public function view_order(Request $request)
    {   
        $data = array(
            'title' => ucfirst($request->id), 
            'order_data' => Orders::select(DB::raw('employee_id, COUNT(orders.id) as total_orders, SUM(total_amount) as total_amount, employee.employee_name, orders.order_by, orders.order_status'))
                       ->join('employee','employee.id','orders.employee_id')
                       ->where('orders.order_by','employee')
                       ->where('orders.order_status',$request->id)
                       ->groupBy('employee_id')
                       ->get(),
        );
        return view('admin.orders.order_users')->with($data);
    }
    public function view_orders(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));  
        $locations = Regions::select(DB::raw('orders.*,customers.customer_name,employee.employee_name,regions.region_name,town.town, @rownum  := @rownum  + 1 AS rownum'))
                    ->join('customers','regions.id','customers.region_id')
                    ->join('orders','orders.customer_id','customers.id')
                    ->join('employee','employee.id','orders.employee_id')
                    ->join('town','town.id','customers.town_id')
                    ->where('orders.order_status','processed');
        $start_date = date('Y/m/d', strtotime($request->date_from));
        $end_date = date('Y/m/d', strtotime($request->date_to));                
        if($request->id3 == 'rout'){
            $rout_id = hashids_decode($request->id);
            $locations = $locations->where('regions.id',$rout_id);
        }if ($request->id3 == 'employee') { 
            $locations = $locations->where('orders.employee_id',hashids_decode($request->id));
        }if($request->id3 == 'customer'){
            $locations = $locations->where('orders.customer_id',hashids_decode($request->id));
        }             
     
           if ($request->isMethod('post')){
                $locations = $locations->orderBy('orders.id','desc')->whereBetween('orders.created_at', [$start_date,$end_date])->get();
            }else{
                $locations = $locations->orderBy('orders.id','desc')->limit(100) ->get();
            }
            
            // echo '<pre>' . $locations . '</pre>';
            // die();
            // dd($locations);
        $data = array(
            'title' => $request->id2,
            'user_id' => $request->id, 
            'user_type' => $request->id3,
            'request'  => $request,
            'locations' => $locations
        );
        return view('admin.orders.all_orders_view')->with($data);
    }
    public function get_user_card(Request $request)
    {
        if ($request->user_type == 'employee') {
            $order_data = Orders::select(DB::raw('employee_id, COUNT(orders.id) as total_orders, SUM(total_amount) as total_amount, employee.employee_name, orders.order_by, orders.order_status'))
                       ->join('employee','employee.id','orders.employee_id')
                       ->where('orders.order_by','employee')
                       ->where('orders.order_status',$request->order_status);
            $group_by = 'employee_id';
        }else if($request->user_type == 'rout'){
            $order_data = Regions::select(DB::raw('COUNT(orders.id) as total_order, SUM(orders.total_amount) AS total_amount,  orders.order_by, orders.order_status, regions.*'))
                        ->join('customers','regions.id','customers.region_id')
                        ->join('orders','orders.customer_id','customers.id')
                        ->where('orders.order_status',$request->order_status)
                        ->orderBy('orders.id');
            $group_by = 'regions.id';
        }else{
            $order_data = Orders::select(DB::raw('customer_id as employee_id, COUNT(orders.id) as total_orders, SUM(total_amount) as total_amount, customers.customer_name as employee_name, orders.order_by, orders.order_status'))
                       ->join('customers','customers.id','orders.customer_id')
                       ->where('orders.order_by',$request->user_type)
                       ->where('orders.order_status',$request->order_status);
            $group_by = 'customer_id';
                       
        }
        if(isset($request->from) && !empty($request->from) && isset($request->to) && !empty($request->to)){
            $order_data->whereBetween('order_date', [$request->from, $request->to]);
        }
        
        $data = array(
            'order_data' => $order_data->groupBy($group_by)->get(),
            'type' => $request->user_type,
        );
        return view('admin.orders.user_card')->with($data);
        
    }

    public function files()
    {
       $data = array(
        'title' => 'File Import/Export',
        'emp_data' => Employees::where('is_active',1)->latest()->get(),
        'route_data' => Regions::latest()->get(),
        );
       return view('admin.orders.files')->with($data);
    }

    // public function file_import(Request $req){
    //     return "DONE";
    // }
    public function exportAzm(Request $request)
    {
        // dd($request->all());
            $d = Orders::select(
            'customers.customer_code',
            'products.product_code',
            'order_details.qty',
            'orders.id as order_id',
            'employee.employee_name'
        )
        ->join('customers', 'customers.id', '=', 'orders.customer_id')
        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
        ->join('products', 'order_details.item_id', '=', 'products.id')
        ->leftJoin('employee', 'employee.id', '=', 'orders.employee_id') // Required for employee_name
        ->whereBetween('orders.order_date', [$request->dateTo, $request->dateForm]);
        
        if ($request->status != 'all') {
            $d = $d->where('orders.order_status', $request->status);
        }
        
        // If user ID filter is enabled
        if (!empty($request->user_id)) {
            $user_id = explode(',', $request->user_id);
            $d = $d->whereIn('orders.employee_id', $user_id);
        }
        
        if (!empty($request->route) && empty($request->town)) {
            $d = $d->where('customers.region_id', hashids_decode($request->route));
        }
        
        if (!empty($request->town)) {
            $d = $d->where('customers.town_id', hashids_decode($request->town));
        }
        
        if (isset($request->order_id) && !empty($request->order_id)) {
            $orderIDs = is_array($request->order_id) ? $request->order_id : explode(',', $request->order_id);
            $d = $d->whereIn('orders.id', $orderIDs);
        }
        
        $results = $d->get();
        $currentDate = date('d-m-Y');
        // File content generation
        $content = "";
        foreach ($results as $row) {
            $orderNumber = $row->order_id;
            $bookerName = $row->employee_name ?? 'Broker';
            $customerCode = $row->customer_code;
            $productCode = $row->product_code;
            $qty = $row->qty;
        
            $content .= "{$orderNumber}~{$currentDate}~{$bookerName}~{$customerCode}~{$productCode}~{$qty}\n";
        }
        // dd($results[0]->employee_name);
    
       $fileName = 'SO_' . date('d-M-Y_H-i-s') . '.azm';

    
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
    public function file_export(Request $request)
    {
        $d = Orders::select(DB::raw('customers.customer_code, products.product_code, order_details.qty,orders.id as order_id'))
        ->join('customers','customers.id','orders.customer_id')
        ->join('order_details','orders.id','order_details.order_id')
        ->join('products','order_details.item_id','products.id')
        ->whereBetween('orders.order_date', array($request->dateTo,$request->dateForm));

        if ($request->status != 'all') {
            $d = $d->where('orders.order_status',$request->status);
        }
        
        if (!empty($request->user_id)) {
            $user_id = explode(',', $request->user_id);
            $d = $d->whereIn('orders.employee_id',$user_id);
        }
        
        if (!empty($request->route) && empty($request->town)){
            $d = $d->where('customers.region_id',hashids_decode($request->route));
        }

        if (!empty($request->town)) {
            $d = $d->where('customers.town_id',hashids_decode($request->town));
        }
        if (isset($request->order_id) && !empty($request->order_id)) {
            $d = $d->whereIn('orders.id',$request->order_id);
        }
        $d = $d->get();

        $content = ''; $order_id = [];
        foreach($d AS $data){
            $order_id[] = $data->order_id;
            $content .= $data->customer_code.",";
            $content .= $data->product_code.",";
            $content .= $data->qty.",";
            $content .= 0;
            $content .= "\r\n";
        } 
        // file name that will be used in the download
        $fileName = $request->dateTo.' to '.$request->dateForm;

        // use headers in order to generate the download
        $headers = [
        'Content-type' => 'text/plain', 
        'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName.'.csv'),
        'Content-Length' => strlen($content)
        ];
        if ($return->change_status == 1) {
             DB::table('orders')
                ->whereIn('id', $order_id)
             //     ->where('order_date', $request->date)
                ->update(['order_status' => 'processed']);
        }
        return response()->make($content, 200, $headers);
    }

    public function file_export2(Request $request)
    {
        $d = Orders::select(DB::raw('customers.customer_code, products.product_code, order_details.qty,orders.id as order_id'))
        ->join('customers','customers.id','orders.customer_id')
        ->join('order_details','orders.id','order_details.order_id')
        ->join('products','order_details.item_id','products.id')
        ->whereBetween('orders.order_date', array($request->dateTo,$request->dateForm));

        if ($request->status != 'all') {
            $d = $d->where('orders.order_status',$request->status);
        }
        
        if (!empty($request->user_id)) {
            $user_id = explode(',', $request->user_id);
            $d = $d->whereIn('orders.employee_id',$user_id);
        }
        
        if (!empty($request->route) && empty($request->town)){
            $d = $d->where('customers.region_id',hashids_decode($request->route));
        }

        if (!empty($request->town)) {
            $d = $d->where('customers.town_id',hashids_decode($request->town));
        }
        if (isset($request->order_id) && !empty($request->order_id)) {
            $d = $d->whereIn('orders.id',$request->order_id);
        }
        $d = $d->get();

        $content = ''; $order_id = [];
        foreach($d AS $data){
            $order_id[] = $data->order_id;
            $content .= $data->customer_code.",";
            $content .= $data->product_code.",";
            $content .= $data->qty.",";
            $content .= 0;
            $content .= "\n";
        } 
        // file name that will be used in the download
        $fileName = $request->dateTo.' to '.$request->dateForm;

        // use headers in order to generate the download
        $headers = [
        'Content-type' => 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
        'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName.'.xlsx'),
        'Content-Length' => strlen($content)
        ];
        DB::table('orders')
                  ->whereIn('id', $order_id)
              //    ->where('order_date', $request->date)
                  ->update(['order_status' => 'processed']);
        return response()->make($content, 200, $headers);
    }
    public function file_export_txt(Request $request)
    { 
        $d = Orders::select(DB::raw('customers.customer_code, products.product_code, order_details.qty,orders.id as order_id'))
        ->join('customers','customers.id','orders.customer_id')
        ->join('order_details','orders.id','order_details.order_id')
        ->join('products','order_details.item_id','products.id')
        ->whereBetween('orders.order_date', array($request->dateTo,$request->dateForm));

        if ($request->status != 'all') {
            $d = $d->where('orders.order_status',$request->status);
        }
        
        if (!empty($request->user_id)) {
            $user_id = explode(',', $request->user_id);
            $d = $d->whereIn('orders.employee_id',$user_id);
        }
        
        if (!empty($request->route) && empty($request->town)){
            $d = $d->where('customers.region_id',hashids_decode($request->route));
        }

        if (!empty($request->town)) {
            $d = $d->where('customers.town_id',hashids_decode($request->town));
        }
        if (isset($request->order_id) && !empty($request->order_id)) {
            $d = $d->whereIn('orders.id',$request->order_id);
        }
        $d = $d->get();

        $content = ''; $order_id = [];
        foreach($d AS $data){
            $order_id[] = $data->order_id;
            $content .= $data->customer_code.",";
            $content .= $data->product_code.",";
            $content .= $data->qty.",";
            $content .= 0;
            $content .= "\r\n";
        } 
        // file name that will be used in the download
        $fileName = $request->dateTo.' to '.$request->dateForm;

        // use headers in order to generate the download
        $headers = [
        'Content-type' => 'text/plain', 
        'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName.'.txt'),
        'Content-Length' => strlen($content)
        ];
        if ($request->change_status == 1) {
            DB::table('orders')
                  ->whereIn('id', $order_id)
               //   ->where('order_date', $request->date)
                  ->update(['order_status' => 'processed']);
        }
        return response()->make($content, 200, $headers);
    }
    
    public function file_export_csv(Request $request)
    {   
        $d = Orders::select(DB::raw('customers.customer_code, products.product_code, order_details.qty,orders.id as order_id'))
        ->join('customers','customers.id','orders.customer_id')
        ->join('order_details','orders.id','order_details.order_id')
        ->join('products','order_details.item_id','products.id')
        ->whereBetween('orders.order_date', array($request->dateTo,$request->dateForm));

        if ($request->status != 'all') {
            $d = $d->where('orders.order_status',$request->status);
        }
        
        if (!empty($request->user_id)) {
            $user_id = explode(',', $request->user_id);
            $d = $d->whereIn('orders.employee_id',$user_id);
        }
        
        if (!empty($request->route) && empty($request->town)){
            $d = $d->where('customers.region_id',hashids_decode($request->route));
        }

        if (!empty($request->town)) {
            $d = $d->where('customers.town_id',hashids_decode($request->town));
        }
        if (isset($request->order_id) && !empty($request->order_id)) {
            $d = $d->whereIn('orders.id',$request->order_id);
        }
        $d = $d->get();

        // $content = ''; $order_id = [];
        // foreach($d AS $data){
        //     $order_id[] = $data->order_id;
        //     $content .= $data->customer_code.",";
        //     $content .= $data->product_code.",";
        //     $content .= $data->qty.",";
        //     $content .= 0;
        //     $content .= "\r\n";
        // } 
        // file name that will be used in the download
        $fileName = $request->dateTo.' to '.$request->dateForm;

        // use headers in order to generate the download
        // $headers = [
        // 'Content-type' => 'text/csv', 
        // 'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName.'.csv'),
        // 'Content-Length' => strlen($content)
        // ];
        if ($request->change_status == 1) {
            DB::table('orders')
                  ->whereIn('id', $order_id)
               //   ->where('order_date', $request->date)
                  ->update(['order_status' => 'processed']);
        }
        // return response()->make($content, 200, $headers);
        // $fileName = 'anas.csv';
        // $headers = array(
        //     "Content-type"        => "text/csv",
        //     "Content-Disposition" => "attachment; filename=$fileName",
        //     "Pragma"              => "no-cache",
        //     "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        //     "Expires"             => "0"
        // );
        $headers = array(
            'Content-Type' => 'text/csv',
          );
          
        //I am storing the csv file in public >> files folder. So that why I am creating files folder
        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }
        $filename =  public_path("files/download.csv");
        $handle = fopen($filename, 'w');

        // fputcsv($handle, [
        //     "Order ID",
        //     "Customer Code",
        //     "Product Code",
        //     "Quantity"
        // ]);

        //adding the data from the array
        foreach ($d as $data) {
            fputcsv($handle, [
                $data->customer_code,
                $data->product_code,
                $data->qty,
            ]);

        }
        fclose($handle);
        
        $name = $request->csv_name.'_transaction.csv';
        
        return Response::download($filename, @$name , $headers);
        // $columns = array('Order ID', 'Customer Code', 'Product Code', 'Quantity');

        // $callback = function() use($d, $columns) {
        //     $file = fopen('php://output', 'w');
        //     fputcsv($file, $columns);

        //     foreach ($d as $key=>$data) {
        //         $row['Order ID']         = $data->order_id;
        //         $row['Customer Code']    = $data->customer_code;
        //         $row['Product Code']     = $data->product_code;
        //         $row['Quantity']         = $data->qty;

        //         fputcsv($file, array($row['Order ID'], $row['Customer Code'], $row['Product Code'], $row['Quantity']));
        //     }
        //     fclose($file);
        // };

        // return response()->stream($callback, 200, $headers);
    }

    public function all_completed_order(Request $request){
       
        $data = array(
            'title' => 'Completed',
            'booked_orders' =>  BookedOrder::select(DB::raw('employee_id, COUNT(booked_orders.invoice_number) as total_orders, SUM(total_amount) as total_amount, employee.employee_name,employee.emp_code, booked_orders.status'))
                       ->join('employee','employee.emp_code','booked_orders.employee_id')
                       ->groupBy('employee_id')
                       ->get(),
        );     
        return view('admin.orders.all_completed_orders')->with($data);
    }

    public function view_completed_order($id){
        $data = array(
            'title' => 'Completed',
            'emp_code' => $id
        );
 // DB::statement(DB::raw('set @rownum=0'));   
 //        $locations = BookedOrder::with(['customers','employees']);
 //            $locations = $locations->where('employee_id',hashids_decode($id));
        
 //        $locations = $locations->latest()->get(['booked_orders.*',
 //            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

 //        dd($locations);
        return view('admin.orders.view_completed_order')->with($data);
    }
    public function complete_list(Request $request)
    {   
        DB::statement(DB::raw('set @rownum=0'));   
        $locations = BookedOrder::with(['customers','employees']);
            $locations = $locations->where('employee_id',hashids_decode($request->emp_id));
        
        $locations = $locations->latest()->get(['booked_orders.*',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

        return datatables()->of($locations)
                    ->addColumn('order_date', function($locations){
                        return \CommonHelpers::date_format_custom($locations->order_process_date);
                    })
                    ->addColumn('employee_name', function($locations){
                            $employee_name = $locations->employees->employee_name;
                        
                        return $employee_name;
                    })
                    ->addColumn('action', function($locations){
                        $button = '';
                        $button = '<a name="edit" href="#" class="btn btn-outline-primary btn-rounded waves-effect waves-light model_btn" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="'.$locations->invoice_number.'"><i class="fas fa-eye"></i></a>';
                       
                        return $button; 
                    })
                    ->rawColumns(['action','employee_name','order_date','delivery_date'])
            ->make(true);
    }
    public function complete_order_details(Request $request)
    {
        $details = BookedOrderDetail::with(['products'])->where('invoice_number',$request->id)->get();
        echo json_encode(array('items' => $details));
    }
    public function get_file_data(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0')); 
        $locations = DB::table('orders')
                    ->join('customers','orders.customer_id','=','customers.id')
                    ->leftJoin('employee','orders.employee_id','=','employee.id')
                    ->leftJoin('regions','customers.region_id','=','regions.id')
                    ->leftJoin('town','customers.town_id','=','town.id')
                    ->whereBetween('orders.order_date', array($request->dateTo,$request->dateForm));
        
        if ($request->order_status != 'all') {
            $locations = $locations->where('orders.order_status',$request->order_status);
        }
        
        if (!empty($request->user_id)) {
            $locations = $locations->whereIn('orders.employee_id',$request->user_id);
        }

        if (!empty($request->route) && empty($request->town)){
            $locations = $locations->where('customers.region_id',hashids_decode($request->route));
        }

        if (!empty($request->town)) {
            $locations = $locations->where('customers.town_id',hashids_decode($request->town));
        }        

        $locations = $locations->latest()->get(['orders.*','customers.customer_name as customer_name','employee.employee_name as employee_name','town.town as town_name','regions.region_name as route_name',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return datatables()->of($locations)
                    ->addColumn('customer_name', function($locations){
                        return '<a style="width: 180px; display:block" name="edit" href="#" class="model_btn" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="'.hashids_encode($locations->id).'">'.$locations->customer_name.'</i></a>';
                    })
                    ->addColumn('order_date', function($locations){
                        return \CommonHelpers::date_format_custom($locations->order_date);
                    })
                    ->addColumn('delivery_date', function($locations){
                        return \CommonHelpers::date_format_custom($locations->delivery_date);
                    })
                    ->addColumn('checkbox', function($locations){
                        return '<input type="checkbox" class="order_id" name="order_id[[]" value="'.$locations->id.'">';
                    })
                    ->addColumn('action', function($locations){
                        return '<a type="button" name="edit" href="#" class="btn btn-outline-primary btn-rounded waves-effect waves-light model_btn" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="'.hashids_encode($locations->id).'"><i class="fas fa-eye"></i></a>';
                    })
                    ->rawColumns(['order_date','delivery_date','customer_name','checkbox','action'])
            ->make(true);   
    }
    
    //this method retrives all order of a customer where order status is pending
    public function pending_orders(Request $req){
        
        if(isset($req->date_from) && isset($req->date_to)){
            $order_data = OrderReturn::with(['Customer','Customer.regions','Customer.town'])->whereHas('order_detail',function($query){
                $query->where('status','pending');
            })->where('status','pending')->whereRaw("DATE(created_at) BETWEEN '".$req->date_from."' AND '".$req->date_to."'")->groupBy('customer_id')->latest()->get();
        
            $from_date = $req->date_from;
            $to_date = $req->date_to;

        }else{
            $current_month = date('m');
            
            $order_data = OrderReturn::with(['Customer','Customer.regions','Customer.town'])
                                        ->whereHas('order_detail',function($query){
                                            $query->where('status','pending');
                                        })->where('status','pending')
                                        ->whereMonth('created_at',$current_month)
                                        ->groupBy('customer_id')
                                        ->latest()
                                        ->get();
            $from_date = date('Y-m-1');
            $to_date = date('Y-m-t');                            
                                        
        }
        
        $data = array(
            'title' => 'Pending Orders',
            'order_data' => $order_data,
            'from_date' => $from_date,
            'to_date'   => $to_date,
        );
        
        return view('admin.orders.pending_orders')->with($data);
    }
    
    public function customer_pending_orders(Request $req){
        
        $data['customer_data'] = Customers::where('id',hashids_decode($req->id))->first();
        $data['title'] = 'Pending Orders';
        
        $data['orders'] = OrderReturn::with(['order_detail.products',
            'order_detail'=>function($query){
                $query->where('status','pending');
            }])
            ->where('customer_id',hashids_decode($req->id))
            ->where('status','pending')
            ->get();

            $data['customer_id'] = hashids_decode($req->id);
            return view('admin.orders.orders_pending')->with($data);

    }

    public function approve_pending_orders(Request $req){
        // dd($req->all());
        if(isset($req->order_return_details_id) && isset($req->customer_id)){
        //summing up all the prices of givei id of order_return_details table
        $prices = OrderReturnDetails::whereIn('id',$req->order_return_details_id)->get();
        $price_total = $prices->sum('subtotal');
        
        //update the status of order_return_details table
        foreach($req->order_return_details_id AS $id){
            $status = OrderReturnDetails::find($id);
            $status->status = 'approved';
            $status->save();
        }
        //inserting the new record in order_return table
        $order_return = new OrderReturn;
        $order_return->customer_id = $req->customer_id;
        $order_return->total_amount = $price_total;
        $order_return->status = 'approved';
        $order_return->save();
        
        $new_order_return_id = $order_return->id;
        
        //inserting new set of items in order_return_table with approved status
        foreach($prices AS $record){
        $new_record = new OrderReturnDetails;
        
        $new_record->order_id = $new_order_return_id;
        $new_record->item_id = $record->item_id;
        $new_record->item_price = $record->item_price;
        $new_record->qty = $record->qty;
        $new_record->subtotal = $record->subtotal;
        $new_record->status = 'approved';
        $new_record->batch_code = $record->batch_code;
        $new_record->expiry_date = $record->expiry_date;
        $new_record->save();
        
        }
        
        return response()->json([
        'success' => 'Orders Approved Successfully',
        'reload' => true,
        ]);
        
        }else{
        return response()->json([
        'error' => 'No check box selected',
        'reload' => false,
        ]);
        }
    
    }
//this function shows the approved orders from order_return table
    public function approved_orders(Request $req){

        $data = array(
        'title' => 'Approved Orders',
        'customers' => Customers::with('regions')->get(),
        );
        
        if(!empty($req->input('customer_id'))){
        
        $data['orders'] = OrderReturn::with(['order_detail','order_detail.products'])
                                    ->whereHas('order_detail',function($query){
                                    $query->where('status','=','approved');
                                    })
                                    ->where('customer_id',hashids_decode($req->customer_id))
                                    ->where('status','approved')
                                    ->get();
        
        $data['customer_id'] = hashids_decode($req->customer_id);
        }
        
        return view('admin.orders.orders_approved')->with($data);
        }
    
    public function search_pending_orders($id){
        
        $data = array(
            'title' => 'Pending Orders',
            'customers' => Customers::get(),
        );
        
        if(!empty($id)){
        
            $data['orders'] = OrderReturn::with(['order_detail.products',
            'order_detail'=>function($query){
            $query->where('status','!=','approved');
            }])
            ->where('customer_id',hashids_decode($id))
            ->where('status','pending')
            ->get();
            
            $data['customer_id'] = hashids_decode($id);
        }
        
            return view('admin.orders.orders_pending')->with($data);
    }

    public function lifted_orders(Request $req)
    {   
        if(isset($req->date_from) && isset($req->date_to)){
            
            $order_data = OrderReturn::with(['Customer','Customer.regions','Customer.town'])
                                        ->whereHas('order_detail',function($query){
                                            $query->where('status','lifted');
                                        })
                                        ->where('status','lifted')
                                        ->whereRaw("DATE(created_at) BETWEEN '".$req->date_from."' AND '".$req->date_to."'")
                                        ->groupBy('customer_id')
                                        ->get();
             $date_from = $req->date_from;
             $date_to = $req->date_to; 
        
        }else{
            $current_month = date('m');
            $order_data = OrderReturn::with(['Customer','Customer.regions','Customer.town'])
                                        ->whereHas('order_detail',function($query){
                                            $query->where('status','lifted');
                                        })
                                        ->where('status','lifted')
                                        ->whereMonth('created_at',$current_month)
                                        ->groupBy('customer_id')
                                        ->get();
            $date_from = date('Y-m-1');
            $date_to = date('Y-m-t');                            
        }
        $data = array(
            'title' => 'Lifted Orders', 
            'order_data' => $order_data,
            'date_from' => $date_from,
            'date_to'   => $date_to,
        );
        return view('admin.orders.lifted_orders')->with($data);
    }


    public function lifted_orders_search(Request $request)
    {   
        $id = $request->id;
        $data = array(
            'title' => 'Lifted Orders',
            'customer_data' => Customers::where('id',hashids_decode($id))->first(),
        );
        
        
            $data['orders'] = OrderReturn::with(['order_detail.products','order_detail.products.Companies'])
            ->whereHas('order_detail',function($query){
                $query->where('status','lifted');
            })
            ->where('customer_id',hashids_decode($id))
            ->where('status','lifted')
            ->get();

            $data['customer_id'] = hashids_decode($id);

            return view('admin.orders.lifted_orders_search')->with($data);
    }

        //this method retrives all order of a customer where order status is rejected
        public function rejected_orders(Request $req){
        
            if(isset($req)){
                $order_data = OrderReturn::with(['Customer','Customer.regions','Customer.town'])->whereHas('order_detail',function($query){
                    $query->where('status','rejected');
                })->where('status','rejected')->whereRaw("DATE(created_at) BETWEEN '".$req->date_from."' AND '".$req->date_to."'")->groupBy('customer_id')->latest()->get();
            
            }
            $data = array(
                'title' => 'Rejected Orders',
                'order_data' => $order_data,
            );
            // dd($order_data);
            return view('admin.orders.rejected_orders')->with($data);
        }
        public function reject_pending_orders(Request $req){
            // dd($req->all());
            if(isset($req->order_return_details_id) && isset($req->customer_id)){
            //summing up all the prices of givei id of order_return_details table
            $prices = OrderReturnDetails::whereIn('id',$req->order_return_details_id)->get();
            $price_total = $prices->sum('subtotal');
            
            //update the status of order_return_details table
            foreach($req->order_return_details_id AS $id){
            $status = OrderReturnDetails::find($id);
            $status->status = 'rejected';
            $status->save();
            }
            //inserting the new record in order_return table
            $order_return = new OrderReturn;
            $order_return->customer_id = $req->customer_id;
            $order_return->total_amount = $price_total;
            $order_return->status = 'rejected';
            $order_return->save();
            
            $new_order_return_id = $order_return->id;
            
            //inserting new set of items in order_return_table with approved status
            foreach($prices AS $record){
            $new_record = new OrderReturnDetails;
            
            $new_record->order_id = $new_order_return_id;
            $new_record->item_id = $record->item_id;
            $new_record->item_price = $record->item_price;
            $new_record->qty = $record->qty;
            $new_record->subtotal = $record->subtotal;
            $new_record->status = 'rejected';
            $new_record->batch_code = $record->batch_code;
            $new_record->expiry_date = $record->expiry_date;
            $new_record->save();
            
            }
            
            return response()->json([
            'success' => 'Orders Rejected Successfully',
            'reload' => true,
            ]);
            
            }else{
            return response()->json([
            'error' => 'No check box selected',
            'reload' => false,
            ]);
            }
        
        }

        public function customer_rejected_orders(Request $req){
            
            $data['customer_data'] = Customers::where('id',hashids_decode($req->id))->first();
            $data['title'] = 'Rejected Orders';
            $data['orders'] = OrderReturn::with(['order_detail.products',
                'order_detail'=>function($query){
                // $query->where('status','!=','approved')->where('status','!=','pending');
                $query->where('status','rejected');
                }])
                ->where('customer_id',hashids_decode($req->id))
                ->where('status','rejected')
                ->get();
    
                $data['customer_id'] = hashids_decode($req->id);
                return view('admin.orders.orders_rejected')->with($data);
        }

        //vendor claimns function

        public function vendorClaim(Request $req){
            
            $data = array(
                'title' => 'Vendor Claim',
                'companies' => Companies::get(),
            );
            return view('admin.orders.vendor_claim')->with($data);
        }

        public function vendor_claim_result(Request $req){
            
        $c = Products::where('company_id',hashids_decode($req->company))->get()->pluck('id','product_name');
        //    dd($c);
        // $arr = array(429,380);
            $claims = OrderReturnDetails::select(DB::raw('SUM(order_return_details.qty) as qty,SUM(order_return_details.subtotal) as subtotal,products.id as product_id,order_return_details.item_price,products.product_name,order_return_details.batch_code,order_return_details.expiry_date,products.product_code'))
                                    ->join('products','products.id','order_return_details.item_id')
                                    ->where('order_return_details.status','lifted')
                                    ->whereIn('order_return_details.item_id',$c)
                                    ->whereBetween('order_return_details.expiry_date',[$req->date_from,$req->date_to])
                                    ->groupBy('batch_code')
                                    ->groupBy('item_id')
                                    // ->orderBy('id','DESC')
                                    ->get();
            // dd($claims);                        
            $data = array(
                'title' => 'Vendor Claim',
                'companies' => Companies::get(),
                'claims'    => $claims,
                'company_id'    => hashids_decode($req->company),
                'date_from'     => $req->date_from,
                'date_to'   => $req->date_to,
            );
            
            return view('admin.orders.vendor_claim')->with($data);    

        }

    public function vendor_claim_export(Request $req){
            
            $batch_codes_arr = explode(",",$req->batch_codes);
            $product_ids_arr = explode(",",$req->product_ids);
            $decoded_product_ids = array_map("hashids_decode",$product_ids_arr);

            $claims = OrderReturnDetails::select(DB::raw('SUM(order_return_details.qty) as qty,SUM(order_return_details.subtotal) as subtotal,  products.id as product_id,order_return_details.item_price,products.product_name,order_return_details.batch_code,order_return_details.expiry_date,products.product_code'))
                                        ->join('products','products.id','order_return_details.item_id')
                                        ->whereIn('batch_code',$batch_codes_arr)
                                        ->whereIn('item_id',$decoded_product_ids)
                                        ->where('status','lifted')
                                        ->groupBy('batch_code')
                                        ->groupBy('item_id')
                                        ->get();
            
        $fileName = 'vendor_claim.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No', 'Product Name', 'Batch Code', 'Expiry Date', 'Product Code', 'Lifted Stock','Unit Price','Subtotal');

        $callback = function() use($claims, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($claims as $key=>$claim) {
                $row['No']  = ++$key;
                $row['Product Name']    = $claim->product_name;
                $row['Batch Code']    = $claim->batch_code;
                $row['Expiry Date']  = CommonHelpers::date_format_custom($claim->expiry_date);
                $row['Product Code']  = $claim->product_code;
                $row['Lifted Stock']  = $claim->qty;
                $row['Unit Price']  = $claim->item_price;
                $row['Subtotal']  = $claim->subtotal;

                fputcsv($file, array($row['No'], $row['Product Name'], $row['Batch Code'], $row['Expiry Date'], $row['Product Code'], $row['Lifted Stock'], $row['Unit Price'], $row['Subtotal']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
            
    }
    
    public function vendor_claim_lifted_orders(Request $req){
        // dd($req->all());    
        $batch_codes_arr = explode(",",$req->batch_codes);
        $product_ids_arr = explode(",",$req->product_ids);
        $add_qty = explode(",",$req->add_qty);
        
        $decoded_product_ids = array_map("hashids_decode",$product_ids_arr);
        $company_id = hashids_decode($req->company);

        $sum_vendor_claim_order_detail_amount = 0;

        $prices = OrderReturnDetails::whereIn('item_id',$decoded_product_ids)
                                    ->whereIn('batch_code',$batch_codes_arr)
                                    ->where('status','lifted')
                                    ->get();
               
        $price_total = $prices->sum('subtotal');

        OrderReturnDetails::whereIn('item_id',$decoded_product_ids)
                            ->whereIn('batch_code',$batch_codes_arr)
                            ->update(['status'=>'vendor_claim']);
                  
        //inserting the new record in vendor_claim_orders table
        $vendor_claim_order = new VendorClaimOrder;
        $vendor_claim_order->company_id = $company_id;
        $vendor_claim_order->total_amount = $price_total;
        $vendor_claim_order->status = 'vendor_claim';
        $vendor_claim_order->save();
        
        $new_order_return_id = $vendor_claim_order->id;

        //inserting new set of items in order_return_table with approved status
        foreach($prices AS $key=>$record){
        $new_record = new VendorClaimOrderDetails;
        
        $new_record->vendor_claim_order_id = $new_order_return_id;
        $new_record->item_id = $record->item_id;
        $new_record->item_price = $record->item_price;
        $new_record->qty = $record->qty + @$add_qty[$key];
        $new_record->subtotal = $record->subtotal + (@$add_qty[$key] * $record->item_price);
        $new_record->status = 'vendor_claim';
        $new_record->batch_code = $record->batch_code;
        $new_record->expiry_date = $record->expiry_date;
        $new_record->save();

        $sum_vendor_claim_order_detail_amount += $record->subtotal + (@$add_qty[$key] * $record->item_price);
        
        }
        VendorClaimOrder::where('id',$vendor_claim_order->id)->update(['total_amount'=>$sum_vendor_claim_order_detail_amount]);
        
        return response()->json([
        'success' => 'Orders Vendor Claimed Successfully',
        'reload' => true,
        ]);
    
    }

    public function vendor_claim_orders(Request $req){
        //dd('done');
        $data = array(
            'title' => 'Vendor Claim Orders',
            'companies' => Companies::get(),
            'vendor_claim_orders'   => VendorClaimOrder::with(['childCompany'])->get(),
        );
        //if company id is set get the the from vendor_claim_orders table
        if(isset($req->company_id)){
            
            $vendor = VendorClaimOrder::with(['childCompany'])->where('company_id',hashids_decode($req->company_id));
            
            //if date is set then get data accoroding to date
            if(isset($req->date_from) && isset($req->date_to)){
                $vendor = $vendor->whereBetween('created_at',[$req->date_from,$req->date_to]);
            }
            
            $vendor = $vendor->latest()->get();
            $data['data'] = $vendor;
        }

        return view('admin.orders.vendor_claim_orders')->with($data);
    }
    
    public function vendor_claim_orders_details($id){
        
        if(!empty($id)){
            
            $vendor = VendorClaimOrderDetails::select(DB::raw('SUM(vendor_claim_order_details.qty) as qty,SUM(vendor_claim_order_details.subtotal) as subtotal,vendor_claim_order_details.expiry_date,vendor_claim_order_details.batch_code,vendor_claim_order_details.item_price,products.product_name'))
                                               ->join('products','products.id','vendor_claim_order_details.item_id') 
                                                ->where('vendor_claim_order_id',hashids_decode($id))
                                                ->orderBy('products.product_name','asc')
                                                ->groupBy('batch_code')
                                                ->groupBy('item_id')
                                                ->get();

            if(VendorClaimOrderDetails::where('vendor_claim_order_id',hashids_decode($id))->exists()){
                $data = array(
                    'title' => 'Vendor Claim Order Details',
                    'vendor_claim_order_details'    => $vendor,
                    'company_name'  => VendorClaimOrder::with(['childCompany'])->where('id',hashids_decode($id))->first(),
                    'order_id'  => $id,
                );
                return view('admin.orders.vendor_claim_order_details')->with($data);
            }
        }
        return redirect()->back();
    }

    public function vendor_claim_order_details_export($id){

        $claims = VendorClaimOrderDetails::select(DB::raw('SUM(vendor_claim_order_details.qty) as qty,SUM(vendor_claim_order_details.subtotal) as subtotal,vendor_claim_order_details.expiry_date,vendor_claim_order_details.batch_code,vendor_claim_order_details.item_price,products.product_name'))
                                        ->join('products','products.id','vendor_claim_order_details.item_id') 
                                        ->where('vendor_claim_order_id',hashids_decode($id))
                                        ->orderBy('products.product_name','asc')
                                        ->groupBy('batch_code')
                                        ->groupBy('item_id')
                                        ->get();
        
        $fileName = 'vendor_claim_details.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No', 'Product Name', 'Expiry Date', 'Batch Code', 'Unit Price', 'Quantity','Subtotal');

        $callback = function() use($claims, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($claims as $key=>$claim) {
                $row['No']  = ++$key;
                $row['Product Name']    = $claim->product_name;
                $row['Expiry Date']  = CommonHelpers::date_format_custom($claim->expiry_date);
                $row['Batch Code']    = $claim->batch_code;
                $row['Unit Price']  = $claim->item_price;
                $row['Quantity']  = $claim->qty;
                $row['Subtotal']  = $claim->subtotal;

                fputcsv($file, array($row['No'], $row['Product Name'], $row['Expiry Date'], $row['Batch Code'], $row['Unit Price'], $row['Quantity'], $row['Subtotal']));
            }

            fclose($file);
        };

         return response()->stream($callback, 200, $headers);
    }

    public function vendor_claim_update(){
        $order_id = OrderReturn::where('status','lifted')->pluck('id');
        // dd($order_id);
        OrderReturnDetails::whereIn('order_id',$order_id)->update(['status'=>'lifted']);
        return "done";
    }
}