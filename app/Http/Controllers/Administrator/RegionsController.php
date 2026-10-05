<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Regions;
use App\Town;
use App\Orders;
use Illuminate\Http\Request;
use CommonHelpers;
use Redirect,
    Response,
    DB,
    Config;

class RegionsController extends AdminController
{
    public function __construct()
    {
       // $this->middleware('is_admin');
    }

    public function index()
    {
        if (!CommonHelpers::rights('routs_view')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = Regions::select(DB::raw('regions.*, COUNT(customers.id) as total_customer'))
                ->leftJoin('customers','regions.id','customers.region_id')
                ->where('customers.deleted_at',null)
                ->groupBy('regions.id')
                ->get(); 
        $order = Orders::select(DB::raw('COUNT(orders.id) as total_order, customers.id as customer_id, regions.id as region_id'))
                ->join('customers','customers.id','orders.customer_id')
                ->join('regions','regions.id','customers.region_id')   
                ->where('regions.deleted_at',null)
                ->where('orders.deleted_at',null) 
                ->groupBy('regions.id')
                ->get();  
                $total_orders = $order->keyBy('region_id');        
        $data = array(
            'title' => 'All Routs',
            'rout_data' => $data,
            'total_orders' => $total_orders,
        );
        return view('admin.regions.all_regions')->with($data);
    }
    
    public function region_list()
    {
        DB::statement(DB::raw('set @rownum=0'));   
        $locations = Regions::latest()->get(['regions.*',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return datatables()->of($locations)
                  ->addColumn('action', function($locations){
                    $button = '';
                    if(CommonHelpers::rights('routs_edit')){
                        $button = '<a type="button" name="edit" href="'.route('admin.region.edit', $locations->hashid).'" class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i class="fas fa-pencil-alt"></i></a>';
                    }if(CommonHelpers::rights('routs_delete')){    
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" onclick="ajaxRequest(this)"  data-url="'.route('admin.region.delete', $locations->hashid).'"  class="btn btn-outline-danger btn-rounded waves-effect waves-light"><i class="fas fa-trash-alt"></i></button>';
                    }    
                        return $button;
                    })
                    ->rawColumns(['action'])
            ->make(true);
        
        
    }

  

    public function edit(Request $request)
    {
        if (!CommonHelpers::rights('routs_edit')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $region = Regions::select(DB::raw('regions.*, COUNT(customers.id) as total_customer, COUNT(orders.id) as total_order'))
                ->leftJoin('customers','regions.id','customers.region_id')
                ->leftJoin('orders','customers.id','orders.customer_id')
                ->where('customers.deleted_at',null)
                ->where('orders.deleted_at',null)
                ->groupBy('regions.id')
                ->get(); 
        $data = array(
            'title' => 'Edit Rout',
            'is_edit' => true,
            'region' => Regions::hashidOrFail($request->id),
            'rout_data' => $region
        );
        return view('admin.regions.all_regions')->with($data);
    }


    public function save(Request $request)
    {
       //checking if its edit or add
        if ($request->category_id) {
            $branch = Regions::hashidFind($request->category_id);
            $msg = 'Rout has been updated successfully';
        } else {
            $branch = new Regions();
            $msg = 'Rout has been added successfully';
        }

        $branch->region_name = $request->region_name;
      //  $branch->area_name = $request->area_name;
      

        $branch->save();
        return response()->json([
            'success' => $msg,
            'redirect' => route('admin.regions')
        ]);
    }

    public function delete(Request $request)
    {
         if (!CommonHelpers::rights('routs_delete')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        Regions::hashidFind($request->id)->delete();
        return response()->json([
            'success' => 'Rout Deleted Successfully',
            'reload' => true
        ]);
    }
    
     function import_csv_regions(Request $request) {


        if ($request->hasFile('csv_file')) {

            $ext = $request->file('csv_file')->getClientOriginalExtension();

            if (in_array($ext, ['csv', 'CSV'])) {

                $path = $request->file('csv_file')->getRealPath();

                $file = $path;

                $catArr = \CommonHelpers::csvToArray($file);

                $data = array();
                for ($i = 0; $i < count($catArr); $i ++) {
                    $check = Regions::where('region_name',$catArr[$i]['rout_name'])->first();
                    if ($check == null){
                        $data[$i] = array('region_name' => $catArr[$i]['rout_name'],"created_at" => date('Y-m-d h:m:s'));
                    }
                }
                    Regions::insert($data);
                
                return response()->json([
                            'success' => 'CSV Imported Successfully',
                            'redirect' => route('admin.regions')
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
    
    // town function
    public function town()
    {
        if (!CommonHelpers::rights('town_view')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
         $data = array(
            'title' => 'All Towns',
            'rout_data' => Regions::latest()->get(),
        );
        return view('admin.regions.all_town')->with($data);
    }

    public function town_list()
    {
        DB::statement(DB::raw('set @rownum=0'));   
        $locations = town::with(['rout'])->latest()->get(['town.*',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return datatables()->of($locations)
                  ->addColumn('action', function($locations){
                    $button= '';
                    if(CommonHelpers::rights('town_edit')){
                        $button = '<a type="button" name="edit" href="'.route('admin.town.edit', $locations->hashid).'" class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i class="fas fa-pencil-alt"></i></a>';
                    }if(CommonHelpers::rights('town_delete')){
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" onclick="ajaxRequest(this)"  data-url="'.route('admin.town.delete', $locations->hashid).'"  class="btn btn-outline-danger btn-rounded waves-effect waves-light"><i class="fas fa-trash-alt"></i></button>';
                    }   
                        return $button;
                    })
                    ->rawColumns(['action'])
            ->make(true);
        
        
    }

    public function town_save(Request $request)
    {
        //checking if its edit or add
        if ($request->category_id) {
            $branch = Town::hashidFind($request->category_id);
            $msg = 'Town has been updated successfully';
        } else {
            $branch = new Town();
            $msg = 'Town has been added successfully';
        }

        $branch->rout_id = hashids_decode($request->rout_id);
        $branch->town = $request->town_name;
      

        $branch->save();
        return response()->json([
            'success' => $msg,
            'redirect' => route('admin.town')
        ]);
    }

    public function town_delete(Request $request)
    {
        if (!CommonHelpers::rights('town_delete')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        Town::hashidFind($request->id)->delete();
        return response()->json([
            'success' => 'Town Deleted Successfully',
            'reload' => true
        ]);
    }
    public function town_edit(Request $request)
    {
        if (!CommonHelpers::rights('town_edit')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'Edit Town',
            'is_edit' => true,
            'region' => Town::hashidOrFail($request->id),
            'rout_data' => Regions::latest()->get(),
        );
        return view('admin.regions.all_town')->with($data);
    }


     function import_csv_town(Request $request) {


        if ($request->hasFile('csv_file')) {

            $ext = $request->file('csv_file')->getClientOriginalExtension();

            if (in_array($ext, ['csv', 'CSV'])) {

                $path = $request->file('csv_file')->getRealPath();

                $file = $path;

                $catArr = \CommonHelpers::csvToArray($file);

                $data = array();

                for ($i = 0; $i < count($catArr); $i ++) {
                    $check = Town::where('town',$catArr[$i]['town'])->first();
                    if ($check == null) {  
                        $rout = Regions::where('region_name',$catArr[$i]['rout'])->first();

                        if ($rout != null) {
                            $rout_id = $rout->id;
                        }else{
                            $region = new Regions();
                            $region->region_name = $catArr[$i]['rout'];
                            $region->save();
                            $rout_id = $region->id;
                        }
                        $data[$i] = array('rout_id' => $rout_id,'town' => $catArr[$i]['town'],"created_at" => date('Y-m-d h:m:s'));
                    }
                }
          //      dd($data);
                Town::insert($data);
                return response()->json([
                    'success' => 'CSV Imported Successfully',
                   'redirect' => route('admin.town')
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
}
