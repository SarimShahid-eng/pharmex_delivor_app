<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;
use App\Customers;
use App\Groups;
use App\Regions;
use App\Tasks;
use App\CompanyTask;
use App\Employees;
use App\Users;
use App\EmployeeTarget;
//use App\CompanyTask;
use CommonHelpers;
use Redirect,
    Response,
    DB,
    Config;

class EmployeesController extends AdminController
{
    public function __construct()
    {
       // $this->middleware('is_admin');
    }
    
    public function index()
    {   
        if (!CommonHelpers::rights('employees_view')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
   // echo    $cus_data = Employees::latest()->get();  die();
        $data = array(
            'title' => 'All Employees',
            'region_data' => Regions::latest()->get(),
        );
        return view('admin.employees.all_employees')->with($data);
    }
    
    public function add()
    {
        if (!CommonHelpers::rights('employees_add')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
    	$data = array(
            'title' => 'Add Employee',
            'region_data' => Regions::latest()->get(),
        );
        return view('admin.employees.add_employees')->with($data);
    }
    public function save(Request $request)
    { 
        if (!$request->customer_id) {
            $rules['username'] = ['required', 'unique:users,user_name,NULL,id,deleted_at,NULL'];
            $rules['emp_code'] = ['required', 'unique:employee,emp_code,NULL,id,deleted_at,NULL'];
            
            $validator = \Validator::make($request->toArray(), $rules);

            if (!$validator->passes()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ]);
            }
        }
        //checking if its edit or add
        if ($request->customer_id) {
            
            $branch = Employees::hashidFind($request->customer_id);
            $user = Users::hashidFind($request->u_id);
            $msg = 'Employee has been updated successfully';
        } else {
            $branch = new Employees();
            $user = new Users();
            $msg = 'Employee has been added successfully';

            $user->user_name = $request->username;
            $user->password = \Hash::make($request->password);
            $user->user_role = 'employee';
            $user->save();
            $branch->u_id = $user->id;
        } 
        $user->user_name = $request->username;
       if($request->password != null){
            
            $user->password = \Hash::make($request->password);
       }
        $user->save();
        
        $branch->employee_name = $request->employee_name;
        $branch->emp_code = $request->emp_code;
        $branch->reminder = $request->reminder;
        $branch->home_phone = $request->home_phone;
        $branch->imei = $request->imei;
        $branch->sim_number = $request->sim_number;
        $branch->category = $request->category;
        $branch->username = $request->username;
       // $branch->password = \Hash::make($request->password);
        // if (!empty($request->password)) {
        //     $pass = base64_encode($request->password);
        //     $pass1 = base64_encode($pass);
        //     $branch->password = $pass1;
        // }
        $branch->shop_close_pattern = $request->shop_close_pattern;
        $branch->no_order_pattern = $request->no_order_pattern;
        $branch->order_recieved_pattern = $request->order_recieved_pattern;
        $branch->address = $request->address;

        $branch->save();
        return response()->json([
            'success' => $msg,
            'redirect' => route('admin.employee')
        ]);
    }

    public function filter_employees(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));   
        $locations = Employees::latest()->get(['employee.*',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return datatables()->of($locations)
                    ->addColumn('view_task', function($locations){
                    $btn ='';
                    if(CommonHelpers::rights('task_view')){
                        $btn = '<a href="#" class="task_btn" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="'.$locations->hashid.'">View Tasks</a> <br>';
                    }     
                        //$btn .=' <a href="#" class="target_btn" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="'.$locations->hashid.'">View Target</a>';
                        return $btn;
                    })
                  ->addColumn('action', function($locations){
                    $button = '';
                        $button = '<a type="button" name="edit" href="#" class="btn btn-outline-primary btn-rounded waves-effect waves-light model_btn" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="'.$locations->hashid.'"><i class="fas fa-eye"></i></a> &nbsp;';
                    if(CommonHelpers::rights('employees_edit')){    
                        $button .= '<a type="button" name="edit" href="'.route('admin.employees.edit', $locations->hashid).'" class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i class="fas fa-pencil-alt"></i></a>';
                    }if(CommonHelpers::rights('employees_delete')){   
                        $checked = ($locations->is_active)? 'checked': '' ; $button .= '';
                        $button .=' <input type="checkbox" class="nopopup" onchange="ajaxRequest(this)" '.$checked. '  data-url="'.route('admin.employee.change_status', $locations->hashid).'"  data-toggle="switchery" data-size="small" data-color="#1bb99a" />'; 
                     //   $button .= '&nbsp;&nbsp;<button type="button" name="edit" onclick="ajaxRequest(this)"  data-url="'.route('admin.employees.delete', $locations->hashid).'"  class="btn btn-outline-danger btn-rounded waves-effect waves-light"><i class="fas fa-trash-alt"></i></button>';
                    }    
                        return $button;
                    })
                    ->rawColumns(['action','view_task'])
            ->make(true);
    }    

    public function model_data(Request $request)
    {
        $items = Employees::where('id', hashids_decode($request->id))->get();
        echo json_encode(array('items' => $items));
        exit;
    }

    public function task_data(Request $request)
    {   
    
        $items = Tasks::with(['Regions'])->where('employee_id', hashids_decode($request->id))->orderBy('month', 'ASC')->get();  //sdd($items);
    
        $html='';
        
        $html.='<table class="table dt_table table-bordered w-100 nowrap responsive"><thead><thead><tr><th>Months</th><th>Routs</th><th>Total Target</th><th>Action</th></tr></thead><tbody>';

        foreach ($items as $k=>$value) {
            $regions = '';
            $html.='<tr>
            <td> '.$value->month_name.' </td>
            <td>'; 
            
            $counter = 0;
            foreach($value->regions AS $k=>$t){
            
                if( $counter == count( $value->regions) - 1) {
                    $regions .= $t->region_name;
                }else{
                    $regions .= $t->region_name.' ,';
                }
                
                $counter = $counter + 1;
            }
            
            
            $html.=''.$regions.'</td>
            <td>'.$value->target.'</td>
            
            <td>';
            $html.='<a href="'.route('admin.employee.view_task', hashids_encode($value->employee_id)).'" name="view"  class="btn btn-outline-primary btn-rounded waves-effect waves-light model_btn"><i class="fas fa-eye"></i></button>';

            $html.='</td>
            </tr>';
        }
        $html.='</tbody></table>';
        echo $html;
        exit;
    }

    public function view_task($id){
      
        $arr = array(
            'data'  => Tasks::with(['Employees','Regions','company_task','company_task.Company'])->where('employee_id', hashids_decode($id))->get(),
        );
    
        return view('admin.employees.view_employee_task')->with($arr);
    }

    public function edit(Request $request)
    {
        $data = array(
            'title' => 'Edit Employees',
            'is_edit' => true,
            'region_data' => Regions::latest()->get(),
            'customer' => Employees::hashidOrFail($request->id),
        );
        
        return view('admin.employees.add_employees')->with($data);
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
               // print_r($catArr); exit();
                $data = array();
                $cus_data = Employees::latest()->get(); 
                for ($i = 0; $i < count($catArr); $i ++) {
                        $customer = Employees::where('employee_name', '=',$catArr[$i]['Employee Name'])->first(); 
                        if ($customer == null) {  // echo "string = ";
                           $pass = base64_encode($catArr[$i]['Password']);
                            $pass1 = base64_encode($pass);
                            $data[$i] = array(
                                'employee_name' => $catArr[$i]['Employee Name'],
                                'reminder' => $catArr[$i]['Reminder'],
                                'home_phone' => '0'.$catArr[$i]['Home Phone'],
                                'imei' => $catArr[$i]['IMEI'],
                                'sim_number' => '0'.$catArr[$i]['Phone Number'],
                                'category' => $catArr[$i]['Category'],
                                'username' => $catArr[$i]['Username'],
                                'password' => $pass1,
                                'shop_close_pattern' => $catArr[$i]['Shop Close Pattern'],
                                'no_order_pattern' => $catArr[$i]['No Order Pattern'],
                                'order_recieved_pattern' => $catArr[$i]['Order Recieved Pattern'],
                                "created_at" => date('Y-m-d h:m:s'),
                                'address' => $catArr[$i]['Address'],
                                
                            );
                            
                        }
                } //print_r($data);
               // exit();
                Employees::insert($data);
                return response()->json([
                            'success' => 'CSV Imported Successfully',
                            'redirect' => route('admin.employee')
                ]);
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
    public function delete(Request $request)
    {
        Employees::hashidFind($request->id)->delete();
        return response()->json([
            'success' => 'Employee Deleted Successfully',
            'reload' => true
        ]);
    }

    public function taget_data(Request $request)
    {
        $items = EmployeeTarget::where('user_id', hashids_decode($request->id))->orderBy('month', 'ASC')->get();  //sdd($items);
        $data['items'] = $items;
        $data['user_id'] = $request->id;
        return view('admin.employees.add_target')->with($data); die();
    }

    public function target_add(Request $request)
    {
        $data = array(
            'title' => 'Add Target',
            'user_id' => $request->id,
        );
        return view('admin.employees.add_target')->with($data);
    }

    public function target_save(Request $request)
    {
      //  dd($request);
        $check = EmployeeTarget::where('month',date('m-Y', strtotime($request->month)))->where('user_id',hashids_decode($request->employee_id))->first();
       // dd($check);
        if (!empty($check)) { 
            $target = EmployeeTarget::find($check->id);
            $msg = 'Target has been updated successfully'; 
        }else{
            $target = new EmployeeTarget(); 
            $msg = 'Target has been added successfully';
        }

            $target->user_id = hashids_decode($request->employee_id);
            $target->month = date('m-Y', strtotime($request->month));
            $target->month_name =  date('F Y', strtotime($request->month));
            $target->target = $request->target;

            $target->save();

         $items = EmployeeTarget::where('user_id', hashids_decode($request->employee_id))->orderBy('month', 'ASC')->get();  //sdd($items);
        $data['items'] = $items;
        $data['user_id'] = $request->employee_id;
        return view('admin.employees.add_target')->with($data); 
        die();    

        return response()->json([
            'success' => $msg,
        //    'reload' => true
        ]);            
    }

    public function target_edit(Request $request)
    {
        $target = EmployeeTarget::hashidOrFail($request->id);
        $data = array(
            'title' => 'Edit Target',
            'is_edit' => true,
            'task_data' => $target,
            'user_id' => hashids_encode($target->user_id),
        );
        return view('admin.employees.add_target')->with($data);
    }

    public function change_status(Request $request)
    {   
        
        $employee = Employees::hashidFind($request->id);
        $status =  (!$employee->is_active)?'1':'0';
        $employee->is_active = $status;
        $employee->save();
       DB::table('employee')
            ->where('id', $employee->u_id)
            ->update(array('is_active' => $status));
        return response()->json([
                    'success' => 'Employee Status Updated Successfully',
                    'reload' => true
        ]);
    }
}
