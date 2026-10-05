<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;
use App\Groups;
use App\Regions;
use App\Tasks;
use App\Employees;
use App\Companies;
use App\CompanyTask;
use App\TaskWorkflow;
use CommonHelpers;
use Redirect,
    Response,
    DB,
    Config;

class TaskController extends AdminController
{
    public function __construct()
    {
        //$this->middleware('is_admin');
    }

    public function index()
    {   
       if (!CommonHelpers::rights('task_add')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
    	$data = array(
            'title' => 'Add Task',
            'group_data' => Groups::latest()->get(),
            'region_data' => Regions::latest()->get(),
            'employee_data' =>  Employees::latest()->get(),
            'task_workflows'=>TaskWorkflow::get(),
            'company_data' =>  Companies::where('parent_id',null)->latest()->get(),
        );
        return view('admin.task.add_task')->with($data);
    }

   public function save(Request $request)
    {  // dd($request); //echo date('F Y', strtotime($request->day)); die();
    	$check = Tasks::where('month',date('m-Y', strtotime($request->day)))->where('employee_id',hashids_decode($request->employee_id))->first();
    // 	dd($request->all());
        $old_region_id = [];
        if (!empty($check)) { 
            $task = Tasks::with(['Regions'])->find($check->id);
            $msg = 'Task has been updated successfully'; 
            $amt = $request->total_amount+$task->target;
            foreach ($task->Regions as $value) {
                $old_region_id[] = $value->id;
            }
        }else{
            $task = new Tasks(); 
            $msg = 'Task has been added successfully';
            $amt = $request->total_amount;
        } 
            $task->employee_id = hashids_decode($request->employee_id);
            $task->month = date('m-Y', strtotime($request->day));
            $task->month_name =  date('F Y', strtotime($request->day));
     //       $task->groups_id = hashids_decode($request->group_id);
            $task->target = $amt;

            $task->save();

            $a = [];
            foreach ($request->target as $key => $value) {
                $a[$key]['task_id'] = $task->id;
                $a[$key]['company_id'] = $value['company_id']; 
                $a[$key]['target_amt'] = $value['target_amt'];
                $a[$key]['month'] = date('m-Y', strtotime($request->day));
            }
     //       print_r($a);
             $b = [];
            foreach ($request->region as $value) {
                $b[] = hashids_decode($value);
            }

            if (!empty($old_region_id)) {
                $town_id = array_merge($old_region_id,$b);
                $task->Regions()->sync($town_id);
            }else{
                $task->Regions()->sync($b);
            }
           CompanyTask::insert($a);
        

        

        return response()->json([
            'success' => $msg,
            'redirect' => route('admin.task')
        ]);
    }
        public function getLastWorkflow()
        {
    	$data = array(
    	         'title' => 'Add Task',
             'is_edit' => true,
            'group_data' => Groups::latest()->get(),
            'region_data' => Regions::latest()->get(),
            'employee_data' =>  Employees::latest()->get(),
            'task_data' => TaskWorkflow::with(['Groups','Employees'])->get(),
            );
            
        return view('admin.task.add_task')->with($data);
        }
    public function edit(Request $request)
    {
        if (!CommonHelpers::rights('task_edit')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'Edit Task',
            'is_edit' => true,
            'group_data' => Groups::latest()->get(),
            'region_data' => Regions::latest()->get(),
            'employee_data' =>  Employees::latest()->get(),
            'task_data' => Tasks::with(['Groups','Regions','Employees'])->hashidOrFail($request->id),
        );
       return view('admin.task.add_task')->with($data);
    }

    public function delete(Request $request)
    {
        if (!CommonHelpers::rights('task_delete')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        Tasks::hashidFind($request->id)->delete();
        return response()->json([
            'success' => 'Task Deleted Successfully',
            'reload' => true
        ]);
    }
    public function fecthWorkflow($hashid){
     $task_data=TaskWorkflow::with(['Groups','Regions','Employees'])->hashidOrFail($hashid);
       	$data = array(
                'title' => 'Set Task From WorkFlow',
                'group_data' => Groups::latest()->get(),
                'region_data' => Regions::latest()->get(),
                'employee_data' =>  Employees::latest()->get(),
                'task_workflows'=>TaskWorkflow::get(),
                'company_data' =>  Companies::where('parent_id',null)->latest()->get(),
                'task_data'=>$task_data,
                'fetchWorkflow'=>true
            );
                    return view('admin.task.add_task')->with($data);
        
    }
}
