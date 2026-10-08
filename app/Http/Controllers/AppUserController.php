<?php

namespace App\Http\Controllers;

use App\AndroidVersion;
use App\BonusPdf;
use App\BookedOrder;
use App\BookedOrderDetail;
use App\CompanyTask;
use App\Customers;
use App\Employees;
use App\Groups;
use App\OrderReturn;
use App\OrderReturnDetails;
use App\Orders;
use App\OrdersDetails;
use App\Policy;
use App\Products;
use App\Regions;
use App\RegionsTasks;
use App\Stock;
use App\Tasks;
use App\Users;
use DB;
use Illuminate\Http\Request;

class AppUserController extends Controller
{
    public function __construct(Request $request)
    {
        @$device_info =  $request->header('info');
        @$uri = $request->path();
        @$ipAddress = $request->ip();
        @$url = $request->url();
        @$headers = apache_request_headers();
        DB::table('api_log')->insert([
            ['user_id' => @$request->emp_id, 'url' => @$url, 'device_info' => json_encode(@$device_info), 'method' => @$uri, 'ip' => @$ipAddress, 'responce' => json_encode(@$headers), 'request_data' => json_encode(@$request->all())],
        ]);
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $device_token = $request->input('device_token');
        $users = Users::where('user_name', $username)->first();
        if (empty($users)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Username does not exist in the system',
            ]);
        }
        if ($users->is_active == 0) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Your account is deactivate! Please contact to Administrator!',
            ]);
        }
        $Hash = \Hash::check($password, $users->password);
        if ($Hash) {
            DB::table('users')->where('id', $users->id)->update(['device_token' => $device_token]);
            if ($users->user_role == 'employee') {
                DB::table('employee')->where('u_id', $users->id)->update(['device_token' => $device_token]);
                $data = Employees::where('u_id', $users->id)->first();

                $task_data = Tasks::with(['Regions'])->where('month', date('m-Y'))->where('employee_id', $data->id)->first();
                $region_id = [];
                $customers = '';
                $CompanyTask = '';
                if (!empty($task_data)) {
                    foreach ($task_data->regions as $key => $value) {
                        $region_id[] = @$value->id;
                    }
                    $customers = DB::table('customers')
                        ->whereIn('region_id', $region_id)
                        ->get();
                    $CompanyTask = CompanyTask::where('task_id', $task_data->id)->with(['Company'])->get();
                }



                $group_data = DB::table('company_task')->where('task_id', @$task_data->id)->get('company_id')->pluck('company_id');
                // $group_data = Groups::with(['Companies'])->where('id', $task_data->groups_id)->first();
                $p = Products::whereIn('company_id', $group_data)->orWhereIn('sub_company_id', $group_data)->latest()->get();

                // $vendor_ids = 

                unset($data['password']);
                return response()->json([
                    'status' => 'success',
                    'data' => $data,
                    'task_data' => $task_data,
                    'task_details' =>  $CompanyTask,
                    'products' => $p,
                    'customers' => $customers,
                ]);
            } else {
                //   DB::table('customers')->where('u_id', $users->id)->update(['device_token' => $device_token]);
                $data = Customers::where('u_id', $users->id)->first();
                return response()->json([
                    'status' => 'success',
                    'data' => $data,
                ]);
            }
        }
        return response()->json([
            'status' => 'error',
            'msg' => 'Wrong password',
        ]);
    }
    public function home(Request $request)
    {
        if (!isset($request->user_id)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The user id is required',
            ]);
        }

        $data = Employees::where('id', $request->user_id)->first();

        $task_data = Tasks::with(['Regions'])->where('month', date('m-Y'))->where('employee_id', $data->id)->first();
        $region_id = [];
        $customers = '';
        $CompanyTask = '';
        if (!empty($task_data)) {
            foreach ($task_data->regions as $key => $value) {
                $region_id[] = @$value->id;
            }
            $customers = DB::table('customers')
                ->whereIn('region_id', $region_id)
                ->get();
            $CompanyTask = CompanyTask::where('task_id', $task_data->id)->with(['Company'])->get();
        }



        $group_data = DB::table('company_task')->where('task_id', @$task_data->id)->get('company_id')->pluck('company_id');
        // $group_data = Groups::with(['Companies'])->where('id', $task_data->groups_id)->first();
        $p = Products::whereIn('company_id', $group_data)->orWhereIn('sub_company_id', $group_data)->latest()->get();

        // $vendor_ids = 

        unset($data['password']);
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'task_data' => $task_data,
            'task_details' =>  $CompanyTask,
            'products' => $p,
            'customers' => $customers,
        ]);
    }
    public function get_All_Datta()
    {
        $data = array(
            'regions' => Regions::latest()->get(),
            'employees' => Employees::latest()->get(),
            'customers' => Customers::with(['regions'])->latest()->get(),
            'groups' =>  Groups::with(['Companies'])->latest()->get(),
            'products' => Products::with(['Companies'])->latest()->get(),
            'tasks' => Tasks::with(['Regions', 'Groups'])->orderBy('month', 'ASC')->get(),
            'bonus_pdf' => BonusPdf::latest()->get()

        );
        return response()->json($data);
    }

    public function customer_order(Request $request)
    {
        $data = json_decode($request->data);
        // $grand_total = collect($data)->sum('total_amount');
        // ---------- Validation: every product must be at least 2000 ----------
        $min_amount = 2000;
        foreach ($data as $value) {
            if ($value->status == 'booked') {
                foreach ($value->order_details as $order_details) {

                    $product_amount = $order_details->total_amount ?? $order_details->subtotal;

                    if ($product_amount < $min_amount) {
                        $product = Products::find($order_details->item_id);
                        if (!$product) {
                            return response()->json([
                                'status' => 'error',
                                'msg' => 'Product not found',
                            ]);
                        }
                        $product_name = $product->product_name ?? ('Item #' . $order_details->item_id);

                        return response()->json([
                            'status' => 'error',
                            'msg' => 'The amount limit of product "' . $product_name . '" is below ' . $min_amount . '. Make it at least ' . $min_amount . '.',
                        ]);
                    }
                }
            }
        }
        // ---------------------------------------------------------------------

        foreach ($data as $value) {
            $orders = new Orders();
            $orders->customer_id = $value->customer_id;
            // $orders->employee_id = $value->employee_id;
            $orders->order_date = date('Y-m-d', strtotime($value->order_date));
            $orders->delivery_date = date('Y-m-d', strtotime($value->delivery_date));
            $orders->total_amount = $value->total_amount;
            $orders->status = $value->status;
            $orders->lat = $value->lat;
            $orders->lng = $value->lng;
            $orders->order_status = $value->status;
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
            'msg' => 'You have successfully booked the order',
        );
        return response()->json($datas);
    }

    public function get_my_order(request $request)
    {
        $data = array(
            'status' => 'success',
            'pending_orders' => Orders::where('customer_id', $request->id)->Where('order_status', '!=', 'completed')->Where('order_status', '!=', 'cancel')->latest()->get(),
            'completed_orders' => Orders::where('customer_id', $request->id)->where('order_status', 'completed')->latest()->get(),
        );
        return response()->json($data);
    }
    public function order_details(request $request)
    {
        $order_data = DB::table('order_details')
            ->join('products', 'order_details.item_id', '=', 'products.id')
            ->where('order_id', $request->id)
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
        $product  = [];
        $customer_id  = 0;
        $orders  = new OrderReturn();
        $details = new OrderReturnDetails();
        $OrderReturn = [];
        $OrderReturnDetails = [];
        $date = date('Y-m-d H:i:s');
        foreach ($data as $value) {
            $customer_id = $value->customer_id;
            $OrderReturn['customer_id'] = $value->customer_id;
            $OrderReturn['total_amount'] = $value->total_amount;
            $OrderReturn['status'] = 'pending';
            $OrderReturn['created_at'] = $date;
            foreach ($value->order_return_details as $order_details) {

                $product[] = $order_details;
            }
        }






        if (!$request->order_id) {
            $order_id = $orders->insertGetId($OrderReturn);
        } else {
            unset($OrderReturn['status']);
            $orders->where('id', $request->order_id)->update($OrderReturn);
            OrderReturnDetails::where('order_id', $request->order_id)->delete();
            $order_id = $request->order_id;
        }

        foreach ($data[0]->order_return_details as $key => $order_details) {

            $OrderReturnDetails[$key]['order_id'] = $order_id;
            $OrderReturnDetails[$key]['item_id'] = $order_details->item_id;
            $OrderReturnDetails[$key]['item_price'] = $order_details->item_price;
            $OrderReturnDetails[$key]['qty'] = $order_details->qty;
            $OrderReturnDetails[$key]['subtotal'] = $order_details->subtotal;
            $OrderReturnDetails[$key]['batch_code'] = @$order_details->batch_code;
            $OrderReturnDetails[$key]['expiry_date'] = date('Y-m-d', strtotime($order_details->expiry_date));
            $OrderReturnDetails[$key]['created_at'] = $date;
        }
        $details->insert($OrderReturnDetails);

        $datas = array(
            'status' => 'success',
            'msg'    => 'Your order return request has been successfully sent',
        );
        return response()->json($datas);
    }

    public function get_expire_date(request $request)
    {

        // $bookedOrderDetail=BookedOrderDetail::with(['booked_order'])->where('product_code',$request->product_code)->where('batch_code',$request->batch_code);
        // $key = $request->customer_code;
        // $f=$bookedOrderDetail->WhereHas('booked_order', function($q) use ($key){    
        //         $q->where('customer_code',$key);
        // })->first();

        // if(@!$f->id){
        //     $datas = array(
        //         'status' => 'error',
        //         'msg'    => 'Product Not Found',
        //     );
        //     return response()->json($datas);
        // }

        // $check_date = date('Y-m-d',strtotime('+60 days'));

        // if($request->is_employe!=1){
        //     if(strtotime($f->expiry_date)<=strtotime($check_date)){
        //         $datas = array(
        //             'status' => 'error',
        //             'msg'    => 'Expiry is under 60 days. You must add of or over 60 days',
        //         );
        //         return response()->json($datas);
        //     }
        // }
        // echo 'hello';
        // exit;

        $q = Stock::where('product_id', $request->product_code)->where('batch_no', $request->batch_code)->first();

        $data = array(
            'status' => 'success',
            'expiry_date' => @$q->expiry_date,
            'rate' => @$q->rate
        );
        return response()->json($data);
    }

    public function get_my_order_return(request $request)
    {
        $data = array(
            'status' => 'success',
            'order_retur' => OrderReturn::where('customer_id', $request->id)->latest()->get(),
        );
        return response()->json($data);
    }
    public function order_return_details(request $request)
    {
        $order_data = DB::table('order_return_details')
            ->join('products', 'order_return_details.item_id', '=', 'products.id')
            ->where('order_id', $request->id)
            ->get();
        $data = array(
            'status' => 'success',
            'order_details' => $order_data,
        );
        return response()->json($data);
    }


    public function order_return_listing(request $request)
    {
        $tasks         = Tasks::where('employee_id', $request->id)->latest()->first();
        if (!$tasks->id) {
            $data = array(
                'status' => 'error',
                'msg'    => 'Employee Not Exists',
            );
            return response()->json($data);
        }
        $regionstasks  = RegionsTasks::where('tasks_id', $tasks->id)->pluck('regions_id')->toArray();
        $customers     = Customers::whereIn('region_id', $regionstasks)->pluck('id')->toArray();
        $order_return  = OrderReturn::with(['order_detail', 'order_detail.products', 'Customer', 'Customer.town'])->whereIn('customer_id', $customers)->where('status', 'approved')->get();
        $data = array(
            'status' => 'success',
            'data' => $order_return,
        );
        return response()->json($data);
    }


    public function mark_as_lifted(request $request)
    {
        $tasks = OrderReturn::where('id', $request->odrer_id)->update(['status' => 'lifted', 'lifted_by' => $request->id]);
        @OrderReturnDetails::where('order_id', $request->odrer_id)->update(['status' => 'lifted']);

        $data = array(
            'status' => 'success',
            'msg'   => 'This order mark as lifted',
        );
        return response()->json($data);
    }
    public function customer_order_cancel(request $request)
    {
        $order_id = $request->order_id;
        $check = Orders::where('id', $order_id)->first();
        if ($check->order_status == 'completed') {
            return response()->json([
                'status' => 'error',
                'msg' => 'This order is not cancel',
            ]);
        }
        if ($check->order_status == 'cancel') {
            return response()->json([
                'status' => 'error',
                'msg' => 'This order alrady cancel',
            ]);
        } else {
            DB::table('orders')->where('id', $order_id)->update(['order_status' => 'cancel']);
            return response()->json([
                'status' => 'success',
                'msg' => 'Your order cancel successfully',
            ]);
        }
    }

    public function SyncData(request $request)
    {
        $task_data =  Tasks::where('employee_id', $request->emp_id)->where('month', date('m-Y'))->first();
        $order_amt = 0;
        $data = json_decode($request->data);
        foreach ($data as $value) {
            $total_amount = round($value->total_amount, 2);
            // $order_check = Orders::where('customer_id',$value->customer_id)->where('order_at',$value->order_at)->first();
            $order_check = Orders::where('customer_id', $value->customer_id)
                ->where('order_date', date('Y-m-d', strtotime($value->order_date)))
                ->where('total_amount', $total_amount)
                ->where('employee_id', $value->employee_id)
                ->first();
            if ($order_check == null) {
                $orders = new Orders();
                $orders->customer_id = $value->customer_id;
                $orders->employee_id = $value->employee_id;
                if ($value->status == 'booked') {
                    $orders->order_date = date('Y-m-d', strtotime($value->order_date));
                    $orders->delivery_date = $value->delivery_date;
                    $orders->total_amount = $total_amount;
                    $order_amt = $order_amt + $total_amount;
                }
                $orders->status = $value->status;
                $orders->lat = $value->lat;
                $orders->lng = $value->lng;
                $orders->order_at = $value->order_at;
                $orders->save();

                if ($value->status == 'booked') {
                    foreach ($value->order_details as $order_details) {
                        $company_target = Products::join('company_task', 'company_task.company_id', 'products.company_id')->where('products.id', $order_details->item_id)->first();
                        $update_company_target = $company_target->achieve_target + $order_details->subtotal;
                        DB::table('company_task')->where('company_id', $company_target->company_id)->where('month', date('m-Y'))->update(['achieve_target' => $update_company_target]);

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
        }
        if ($order_amt == 0) {
            $achieve_target = $task_data->achieve_target;
        } else {
            $achieve_target = $task_data->achieve_target + $order_amt;
        }
        DB::table('tasks')->where('id', $task_data->id)->update(['achieve_target' => $achieve_target]);
        $datas = array(
            'status' => 'success',
            'achieve_target' => $achieve_target,
        );
        return response()->json($datas);
    }

    public function task_statistics(Request $request)
    {
        $orders = Orders::where('employee_id', $request->user_id)->where('order_status', 'booked')->whereBetween('order_date', [date('Y-m-d', strtotime("first day of this month")), date('Y-m-d', strtotime("last day of this month"))])->get();
        $task_data = Tasks::where('month', date('m-Y'))->where('employee_id', $request->user_id)->first();
        $data = array(
            'status' => 'success',
            'total_orders' => @$orders->count('id'),
            'total_value_booked' => @$orders->sum('total_amount'),
            'total_target' => @$task_data->target,
            'remaining_target' => @$task_data->target - $orders->sum('total_amount'),
            'achieve_target' => @$task_data->achieve_target,
        );
        return response()->json($data);
    }

    public function task_details(Request $request)
    {
        $task_data = Tasks::where('month', date('m-Y'))->where('employee_id', $request->user_id)->first();
        $data = array(
            'status' => 'success',
            'total_target' => @$task_data->target,
            'achieve_target' => @$task_data->achieve_target,
            'task_details' =>  CompanyTask::where('task_id', @$task_data->id)->with(['Company'])->get(),
        );
        return response()->json($data);
    }

    public function get_todat_order(Request $request)
    {
        $today_date = date('Y-m-d');
        $order = Orders::select(DB::raw('COUNT(id) as total_order, SUM(total_amount) as total_amount'))->where('employee_id', $request->id)->whereRaw("DATE(created_at) = '" . $today_date . "'")->first();

        $data = array(
            'status' => 'success',
            'total_order' => @$order->total_order ?? 0,
            'total_amount' => @$order->total_amount ?? 0,
        );
        return response()->json($data);
    }

    public function androidVersion()
    {
        $data = array(
            'version' => AndroidVersion::first(),
        );
        return response()->json($data);
    }
    public function get_policies()
    {
        return response()->json([
            'status' => true,
            'data'   => Policy::where('is_active', 1)->first(['id', 'title', 'policies']),
        ]);
    }
}
