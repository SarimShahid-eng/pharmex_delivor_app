<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;
use App\Customers;
use App\Regions;
use App\Users;
use App\Town;
use CommonHelpers;
use Redirect,
    Response,
    DB,
    Config;


class CustomersController extends AdminController
{
    public function __construct()
    {
       // $this->middleware('is_admin');
    }

    public function index()
    {
        if (!CommonHelpers::rights('customer_view')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
    	$data = array(
            'title' => 'All Customers',
            'region_data' => Regions::latest()->get(),
        );
        return view('admin.customers.all_customers')->with($data);
    }
    public function add()
    {
        if (!CommonHelpers::rights('customer_add')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
    	$data = array(
            'title' => 'Add Customers',
            'region_data' => Regions::latest()->get(),
        );
        return view('admin.customers.add_customers')->with($data);
    }

    public function save(Request $request)
    { 

       if (!$request->customer_id) {
            $rules['username'] = ['required', 'unique:users,user_name,NULL,id,deleted_at,NULL'];
            $rules['customer_code'] = ['required', 'unique:customers,customer_code,NULL,id,deleted_at,NULL'];
            $validator = \Validator::make($request->toArray(), $rules);

            if (!$validator->passes()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ]);
            }
        }
    	//checking if its edit or add
        if ($request->customer_id) {
            $branch = Customers::hashidFind($request->customer_id);
            $user = Users::hashidFind($request->user_id);
            $msg = 'Customer has been updated successfully';
        } else {
            $branch = new Customers();
            $user = new Users();
            $msg = 'Customer has been added successfully';
        }
        
        $user->user_name = $request->username;
        if (!empty($request->password)) {
            $user->password = \Hash::make($request->password);
        }
        
        $user->user_role = 'customer';
        $user->save();
        
        $branch->u_id = $user->id;
        $branch->customer_code = $request->customer_code;
        $branch->customer_name = $request->customer_name;
        $branch->contact_person = $request->contact_person;
        $branch->ph_num = $request->ph_num;
        $branch->region_id = hashids_decode($request->region_id);
        $branch->town_id = hashids_decode($request->town_id);
        $branch->address = $request->location;
        $branch->license_no = $request->license_no;
        $branch->license_exp = $request->license_exp;
        $branch->value = $request->value;
        $branch->username = $request->username;
        
        $branch->save();
        return response()->json([
            'success' => $msg,
            'redirect' => route('admin.customers')
        ]);
    }
    public function customers_list(Request $request)
    { 
    	DB::statement(DB::raw('set @rownum=0'));   
        $locations = Customers::with(['regions','town'])->latest()->get(['customers.*',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return datatables()->of($locations)
                  ->addColumn('action', function($locations){
                    $button = '';
                    if(CommonHelpers::rights('customer_edit')){
                        $button = '<a type="button" name="edit" href="'.route('admin.customer.edit', $locations->hashid).'" class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i class="fas fa-pencil-alt"></i></a>';
                        return $button;
                    }    
                    })->addColumn('status', function($locations){
                        $checked = ($locations->is_active)? 'checked': '' ; $button = '';
                        if(CommonHelpers::rights('customer_edit')){
                        $button=' <input type="checkbox" class="nopopup" onchange="ajaxRequest(this)" '.$checked. '  data-url="'.route('admin.customer.change_status', $locations->hashid).'"  data-toggle="switchery" data-size="small" data-color="#1bb99a" />';
                        }
                        return $button;
                    })->addColumn('lic_exp', function($locations){
                        $exp_lic = \CommonHelpers::date_format_custom($locations->license_exp);
                        return $exp_lic;
                    })
                    ->rawColumns(['action','status','lic_exp'])
            ->make(true);
    }

    public function filter_customers(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));   
        $locations = Customers::with(['regions'])->latest()->where('customers.region_id', hashids_decode($request->region_id))->get(['customers.*',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
       
        return datatables()->of($locations)
                  ->addColumn('action', function($locations){
                        $button = '<a type="button" name="edit" href="'.route('admin.customer.edit', $locations->hashid).'" class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i class="fas fa-pencil-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" onclick="ajaxRequest(this)"  data-url="'.route('admin.customer.delete', $locations->hashid).'"  class="btn btn-outline-danger btn-rounded waves-effect waves-light"><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
            ->make(true);
    }

    public function edit(Request $request)
    {
        if (!CommonHelpers::rights('customer_edit')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'Add Customers',
            'is_edit' => true,
            'region_data' => Regions::latest()->get(),
            'customer' => Customers::hashidOrFail($request->id),
            'town_data' => Town::latest()->get(),
        );
        return view('admin.customers.add_customers')->with($data);
    }

    public function delete(Request $request)
    {
        if (!CommonHelpers::rights('customer_edit')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = Customers::hashidFind($request->id);
         Users::where('id',$data->u_id)->delete();  
        Customers::hashidFind($request->id)->delete();  
        return response()->json([
            'success' => 'Customer Deleted Successfully',
            'reload' => true
        ]);
    }

    public function import_csv_customer(Request $request)
    { 
        set_time_limit(0);
         if ($request->hasFile('csv_file')) {

            $ext = $request->file('csv_file')->getClientOriginalExtension();

            if (in_array($ext, ['csv', 'CSV'])) {

                $path = $request->file('csv_file')->getRealPath();

                $file = $path;

                $catArr = \CommonHelpers::csvToArray($file);
                
                $data = array(); $not_insert = [];
                for ($i = 0; $i < count($catArr); $i ++) {
                    $check_user =  Users::where('user_name','=',$catArr[$i]['username'])->first();
                    if($check_user == null){
                        $customer = Customers::where('customer_code', '=',$catArr[$i]['customer_code'])->first(); 
                        if ($customer == null) {  
                            $user = new Users();
                            $user->user_name = $catArr[$i]['username'];
                            $user->password = \Hash::make($catArr[$i]['password']);
                            $user->user_role = 'customer';
                            $user->save();

                           //    $town_id = Town::Where('town',$catArr[$i]['town'])->first();
                            $data[$i] = array(
                                'u_id' => $user->id,
                                'customer_code' => $catArr[$i]['customer_code'],
                                'customer_name' => $catArr[$i]['name'],
                                'address' => $catArr[$i]['address'],
                                'contact_person' => $catArr[$i]['contact_person'],
                                'ph_num' => $catArr[$i]["ph_no"],
                                'region_id' => hashids_decode($request->region_id),
                                'town_id' => hashids_decode($request->town_id),
                                'username' => $catArr[$i]['username'],
                                'user_role' => 'customer',
                                'license_no' => $catArr[$i]['lic_no'],
                                'license_exp' => date('Y-m-d',strtotime($catArr[$i]['lic_exp'])),
                                'value' => $catArr[$i]['value'],
                                "created_at" => date('Y-m-d h:m:s'),
                                
                            );
                            
                        }
                    }else{
                        $not_insert[] = $catArr[$i]['name'];
                    }    
                }
            //    dd($data);
                Customers::insert($data);
                if(!empty($not_insert)){
                    return response()->json([
                        'success' => 'CSV Imported, This users not insert '. implode(' , ',$not_insert) ,
                  //      'redirect' => route('admin.customers')
                    ]);
                }else{
                    return response()->json([
                        'success' => 'CSV Imported Successfully',
                        'redirect' => route('admin.customers')
                    ]);
                }
                
            } else {
                return response()->json([
                            'error' => 'file type not allowed'
                ]);
            }
            return response()->json([
                        'error' => 'Select CSV File'
            ]);
        }
    }
    public function get_town(Request $request)
    {  
        $rout_id = hashids_decode($request->rout_id);
        $town = Town::where('rout_id',$rout_id)->get();
        $html = '';
        $html.='<option value="">Select...</option>';
        if (!empty($town)) {
            foreach ($town as $value) {
                $html.='<option value="'.$value->hashid.'">'.$value->town.'</option>';
            }
        } echo $html;
    }
    public function change_status(Request $request)
    {
        $customer = Customers::hashidFind($request->id);
        $status =  (!$customer->is_active)?'1':'0';
        $customer->is_active = $status;
        $customer->save();
       DB::table('users')
            ->where('id', $customer->u_id)
            ->update(array('is_active' => $status));
        return response()->json([
                    'success' => 'Customer Status Updated Successfully',
                    'reload' => true
        ]);
    }
}