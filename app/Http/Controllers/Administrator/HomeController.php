<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use CommonHelpers;
use App\Customers;
use App\Products;
use App\Employees;
use App\Orders;
use App\AndroidVersion;

class HomeController extends AdminController {

    public function index() {

         // $user = auth()->user();
        //  if($user->is_admin){
        if (auth()->user()->user_role == 'company') {
            return view('admin.dashboards.company');
        }else{
            
            $data = $this->admin_home(); 
            return view('admin.dashboards.admin')->with($data);
        }
        //}
        //abort('404');
    }

    private function admin_home() {
        $data = array(
            'booked_order' =>  Orders::where('order_status','booked')->count(),
            'processed_order' =>  Orders::where('order_status','processed')->count(),
            'completed_order' =>  Orders::where('order_status','completed')->count(),
            'cancel_order' =>  Orders::where('order_status','cancel')->count(),
            'total_order' => Orders::count(),
            'all_orders' => Orders::with(['customer','customer.town','order_detail'])->where('order_status','booked')->where('order_by','customer')->latest()->get(),
            'version'   => AndroidVersion::take(1)->first(),
        );
        // echo '<pre>';
        // print_r($data['count']);
        // die();
        // dd($data['all_orders']);
        return array(
            'title' => 'Dashboad',
            'data' => $data,
        );
    }

    public function storeAndroidVersion(Request $req){
        
        $rules = [
            'android_version'   => ['required','numeric']
        ];

        $validator = \Validator::make($req->all(),$rules);

        if($validator->fails()){
            return ['errors'    => $validator->errors()];
        }
        
        if(isset($req->version_id) && !empty($req->version_id)){
            $version = AndroidVersion::find(hashids_decode($req->version_id));
        }else{
            $version = new AndroidVersion; 
        }
        
        $version->android_version = $req->android_version;
        $version->save();

        return response()->json([
            'success'   => 'Version Updated Successfully',
            'reload'    => TRUE,
        ]);
    }

}
