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
            $companies = Companies::whereNull('parent_id')
        ->orderBy('id')
        ->paginate(16);

        $totals = DB::table('order_details')
            ->join('products', 'products.id', '=', 'order_details.item_id')
            ->whereIn('products.company_id', $companies->pluck('id'))
            ->whereNull('order_details.deleted_at')
            ->whereNull('products.deleted_at')
            ->groupBy('products.company_id')
            ->selectRaw('products.company_id, SUM(order_details.subtotal) as amt')
            ->pluck('amt', 'company_id');

        $companies->each(function ($c) use ($totals) {
            $c->amt = $totals[$c->id] ?? 0;
        });

        $data = array(
            'title' => 'All Companies',
            'company' => $companies,
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
