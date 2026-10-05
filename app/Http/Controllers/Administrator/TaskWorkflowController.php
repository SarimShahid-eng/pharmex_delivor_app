<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;
use App\Groups;
use App\Regions;
use App\Tasks;
use App\Employees;
use App\Companies;
use App\CompanyTaskWorkflow;
use App\TaskWorkflow;
use CommonHelpers;
use Redirect,
    Response,
    DB,
    Config;

class TaskWorkflowController extends AdminController
{
    public function __construct()
    {
        // dd('ss');
        //$this->middleware('is_admin');
    }
 public function index()
    {
        $data = array(
            'title' => 'All Categories'
        );
        return view('admin.task_workflow.index')->with($data);
    }
    
     public function task_workflow_list()
{
    DB::statement(DB::raw('set @rownum=0'));

    $task = TaskWorkflow::latest()
        ->get([
            'task_workflow.*',
            DB::raw('@rownum := @rownum + 1 AS rownum')
        ]);

    return datatables()->of($task)

       
        // Add title column (whatever you want to show)
        ->addColumn('title', function ($task) {
            return $task->title; // or $task->title if exists
        })
         // Add employee column (whatever you want to show)
        ->addColumn('employee', function ($task) {
            return $task->Employees->employee_name; // or $task->title if exists
        })
        // Month name column
        ->addColumn('month_name', function ($task) {
            return $task->month_name;
        })

        // Action buttons column
        ->addColumn('action', function ($task) {
            $button  = '<a href="'.route('admin.task_workflow.edit', $task->hashid).'" 
                            class="btn btn-outline-primary btn-rounded waves-effect waves-light">
                            <i class="fas fa-pencil-alt"></i>
                        </a>';

            $button .= '&nbsp;&nbsp;&nbsp;<button type="button" 
                            onclick="ajaxRequest(this)"  
                            data-url="'.route('admin.task_workflow.delete', $task->hashid).'"
                            class="btn btn-outline-danger btn-rounded waves-effect waves-light">
                            <i class="fas fa-trash-alt"></i>
                        </button>';

            return $button;
        })

        ->rawColumns(['action'])
        ->make(true);
}
    public function create()
    {   
    //   if (!CommonHelpers::rights('task_add')) {
    //         if (!$this->authorize('create', 'App\Admin')) {
    //             abort('403', env('ERROR_403'));
    //         }
    //     }
    	$data = array(
            'title' => 'Task Workflow Manage',
            'group_data' => Groups::latest()->get(),
            'region_data' => Regions::latest()->get(),
            'employee_data' =>  Employees::latest()->get(),
            'company_data' =>  Companies::where('parent_id',null)->latest()->get(),
        );
        return view('admin.task_workflow.create')->with($data);
    }
    public function save(Request $request)
        {
            $hashid=$request->task_id;
            if($hashid){
            $decodeId = hashids_decode($hashid);
            }    
        $employee_id = hashids_decode($request->employee_id);
            $month = date('m-Y', strtotime($request->day));
            $month_name = date('F Y', strtotime($request->day));
        
            $old_region_id = [];
        
            // Determine the task to update or create
            if (!empty($request->task_id)) {
                // Editing an existing task explicitly
                $task = TaskWorkflow::with(['Regions', 'company_task'])->findOrFail(hashids_decode($request->task_id));
            } else {
                // Check if a task exists for this employee & month
                $task = TaskWorkflow::with(['Regions', 'company_task'])
                    ->where('employee_id', $employee_id)
                    ->where('month', $month)
                    ->first();
                if (!$task) {
                    $task = new TaskWorkflow();
                }
            }
        
            $msg = $task->exists ? 'Task has been updated successfully' : 'Task has been added successfully';
        
            // Preserve old regions if task exists
            if ($task->exists) {
                $old_region_id = $task->Regions->pluck('id')->toArray();
            }
            // else {
            //     // $amt = $request->total_amount;
            // }
        $amt = $request->total_amount;
            // Update TaskWorkflow fields
            $task->employee_id = $employee_id;
            $task->month = $month;
            $task->month_name = $month_name;
            $task->title = $request->title;
            $task->target = $amt;
            $task->save();
        
            // Sync regions
            $new_regions = array_map('hashids_decode', $request->region);
            // $task->Regions()->sync(array_unique(array_merge($old_region_id, $new_regions)));
        $new_regions = array_map('hashids_decode', $request->region);
$task->Regions()->sync($new_regions); // this will replace old regions with new ones
            // Prepare company task data
            $companyTasks = [];
            foreach ($request->target as $key => $value) {
                $companyTasks[$key] = [
                    'task_workflow_id' => $task->id,
                    'company_id'       => $value['company_id'],
                    'target_amt'       => $value['target_amt'],
                    'month'            => $month
                ];
            }
           if($hashid){
            // Remove old company task entries for this task
           CompanyTaskWorkflow::where('task_workflow_id', $decodeId)->forceDelete();
           }
            // // Insert the new/updated company tasks
            CompanyTaskWorkflow::insert($companyTasks);
        
            return response()->json([
                'success' => $msg,
                'redirect' => route('admin.task_workflow.index')
            ]);
        }

 
    public function edit($id)
    {

        if (!CommonHelpers::rights('task_add')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }

    if ($id) {
        // Using Laravel 6 + DianujHashidsTrait
        $task_data = TaskWorkflow::with(['Groups','Regions','Employees'])->hashidOrFail($id);
        // dd($task_data);
    }

     // Fetch related data for selects
    $employee_data = \App\Employees::all();  // or add conditions if needed
    $region_data   = \App\Regions::all();
    $company_data  =  Companies::where('parent_id',null)->latest()->get();

    return view('admin.task_workflow.create')->with([
        'title'         => 'Edit Task Workflow',
        'is_edit'       => true,
        'task_data'     => $task_data,
        'employee_data' => $employee_data,
        'region_data'   => $region_data,
        'company_data'  => $company_data,
    ]);
    }
public function delete($id)
{
    // Decode the task ID
    $taskId = hashids_decode($id);

    // Find the task
    $task = TaskWorkflow::with(['Regions', 'company_task'])->find($taskId);

    if (!$task) {
        return response()->json([
            'error' => 'Task not found.'
        ], 404);
    }

    // 1. Detach all related regions
    $task->Regions()->detach();

    // 2. Delete all related company tasks
    CompanyTaskWorkflow::where('task_workflow_id', $task->id)->forceDelete();

    // 3. Delete the task itself
    $task->forceDelete(); // permanently deletes task, bypassing soft deletes

    return response()->json([
        'success' => 'Task and all related data have been deleted successfully.'
    ]);
}
    // public function delete(Request $request)
    // {
    //     if (!CommonHelpers::rights('task_delete')) {
    //         if (!$this->authorize('create', 'App\Admin')) {
    //             abort('403', env('ERROR_403'));
    //         }
    //     }
    //     Tasks::hashidFind($request->id)->delete();
    //     return response()->json([
    //         'success' => 'Task Deleted Successfully',
    //         'reload' => true
    //     ]);
    // }
}
