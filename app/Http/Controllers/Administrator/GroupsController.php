<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;
use App\Groups;
use App\Customers;
use CommonHelpers;
use App\Companies;
use Redirect,
    Response,
    DB,
    Config;

class GroupsController extends AdminController
{
    public function __construct()
    {
     //   $this->middleware('is_admin');
    }

    public function index()
    { 
        // $vendors = [];
        // $vendor = Companies::with(['parent'])->where('parent_id',null)->get(); 
        // foreach ($vendor as $k => $value) {
        //    $vendors[$k]['id'] = $value->id;
        //    $vendors[$k]['text'] = $value->company_name;
        //    $vendors[$k]['level'] = 1;
        //    foreach ($value->parent as $key => $groups) {
        //        $vendors[$key+1]['id'] = $groups->id;
        //        $vendors[$key+1]['text'] = $groups->company_name;
        //        $vendors[$key+1]['level'] = 2;
        //    }
        // } json_encode($vendors)
        if (!CommonHelpers::rights('group_view')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
       $data = array(
            'title' => 'All Groups',
            'customers_data' => Companies::with(['parent'])->where('parent_id',null)->get(),
        );
        return view('admin.groups.all_groups')->with($data);
    }

    public function group_list(Request $request)
    { 
        DB::statement(DB::raw('set @rownum=0'));   
        $locations = Groups::with(['Companies'])->latest()->get(['groups.*',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return datatables()->of($locations)
                  ->addColumn('venders', function($locations){
                        $venders = '';
                        for ($i=0; $i <count($locations->Companies) ; $i++) { 
                           $venders .= $locations->Companies[$i]->company_name;
                           if ($i < count($locations->Companies)-1) {
                               $venders .= ', ';
                           }
                        }
                        return $venders;
                    })
                  ->addColumn('action', function($locations){
                    $button = '';
                    if(CommonHelpers::rights('group_edit')){
                        $button = '<a type="button" name="edit" href="'.route('admin.group.edit', $locations->hashid).'" class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i class="fas fa-pencil-alt"></i></a>';
                    }if(CommonHelpers::rights('group_delete')){   
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" onclick="ajaxRequest(this)"  data-url="'.route('admin.group.delete', $locations->hashid).'"  class="btn btn-outline-danger btn-rounded waves-effect waves-light"><i class="fas fa-trash-alt"></i></button>';
                    }    
                        return $button;
                    })
                    ->rawColumns(['action'],['venders'])
            ->make(true);
    }

    public function save(Request $request)
    {
        if ($request->group_id) {
            $group = Groups::hashidFind($request->group_id);
            $msg = 'Group has been updated successfully';
        }else {
            $group = new Groups();
            $msg = 'Group has been added successfully';
        }

        
        $group->group_name = $request->group_name;
        $group->save();
        
        $a = [];
        foreach ($request->venders as $value) {
            $a[] = hashids_decode($value);
        }
        $group->Companies()->sync($a);
        
        return response()->json([
            'success' => $msg,
            'redirect' => route('admin.groups')
        ]);
    }
    public function edit(Request $request)
    {
        if (!CommonHelpers::rights('group_edit')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'Edit Group',
            'is_edit' => true,
            'customers_data' =>  Companies::with(['parent'])->where('parent_id',null)->get(),
            'group_data' => Groups::with(['Companies'])->hashidOrFail($request->id),
        );
     //   echo Groups::with(['Companies'])->hashidOrFail($request->id); die();
        return view('admin.groups.all_groups')->with($data);
    }

    public function delete(Request $request)
    {
        Groups::hashidFind($request->id)->delete();
        return response()->json([
            'success' => 'Group Deleted Successfully',
            'reload' => true
        ]);
    }
}
