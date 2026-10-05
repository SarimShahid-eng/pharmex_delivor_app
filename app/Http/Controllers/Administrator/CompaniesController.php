<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;
use App\Companies;
use CommonHelpers;
use Redirect,
    Response,
    DB,
    Config;

class CompaniesController extends AdminController
{
    public function __construct()
    {
       // $this->middleware('is_admin');
    }
    
    public function index()
    {   
        if (!CommonHelpers::rights('company_view')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'All Companies',
            'company' => Companies::select(DB::raw('companies.*, SUM(order_details.subtotal) as amt'))
                ->leftJoin('products','companies.id','products.company_id')
                ->leftJoin('order_details','order_details.item_id','products.id')
                ->where('order_details.deleted_at',null)
                ->where('products.deleted_at',null)
                ->where('companies.parent_id',null)
                ->groupBy('companies.id')
                ->paginate(16),
        );
      //  dd($data);
        return view('admin.companies.all_companies')->with($data);
    }

    public function add()
    {
        if (!CommonHelpers::rights('company_add')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'Add Company',
            'company_data' => Companies::where('parent_id',null)->latest()->get(),
        );
        return view('admin.companies.add_companies')->with($data)   ;
    }

    public function save(Request $request)
    {
    	//checking if its edit or add
        if ($request->customer_id) {
            $branch = Companies::hashidFind($request->customer_id);
            $msg = 'Company has been updated successfully';
        } else {
            $branch = new Companies();
            $msg = 'Company has been added successfully';
        }

        $branch->company_name = $request->company_name;
        $branch->ph_num = $request->ph_num;
        $branch->division = $request->division;
        if ($request->parent_id != '') {
            $branch->parent_id = hashids_decode($request->parent_id);
        }
        $branch->save();
        return response()->json([
            'success' => $msg,
            'redirect' => route('admin.company')
        ]);
    }
    public function Companies_list(Request $request)
    { 
    	DB::statement(DB::raw('set @rownum=0'));   
        $locations = Companies::latest()->get(['companies.*',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return datatables()->of($locations)
                  ->addColumn('action', function($locations){
                    $button = '';
                    if(CommonHelpers::rights('company_edit')){   
                        $button = '<a type="button" name="edit" href="'.route('admin.company.edit', $locations->hashid).'" class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i class="fas fa-pencil-alt"></i></a>';
                    }if(CommonHelpers::rights('company_delete')){ 
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" onclick="ajaxRequest(this)"  data-url="'.route('admin.company.delete', $locations->hashid).'"  class="btn btn-outline-danger btn-rounded waves-effect waves-light"><i class="fas fa-trash-alt"></i></button>';
                    }    
                        return $button;
                    })
                    ->rawColumns(['action'])
            ->make(true);
    }

    public function edit(Request $request)
    {
        if (!CommonHelpers::rights('company_edit')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'Add Customers',
            'is_edit' => true,
          'company_data' => Companies::where('parent_id',null)->latest()->get(),
            'customer' => Companies::hashidOrFail($request->id),
        );
        return view('admin.companies.add_companies')->with($data);
    }
    public function delete(Request $request)
    {
        if (!CommonHelpers::rights('company_delete')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        Companies::hashidFind($request->id)->delete();
        return response()->json([
            'success' => 'Company Deleted Successfully',
            'reload' => true
        ]);
    }
}
