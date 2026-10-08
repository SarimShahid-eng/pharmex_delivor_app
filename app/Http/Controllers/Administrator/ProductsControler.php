<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;
use App\Customers;
use App\Products;
use App\Groups;
use App\Companies;
use CommonHelpers;
use Redirect,
    Response,
    DB,
    Config;

class ProductsControler extends AdminController
{
    public function __construct()
    {
        //  $this->middleware('is_admin');
    }

    public function index()
    {

        if (!CommonHelpers::rights('product_view')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'All Products',
            'company_data' => Companies::where('parent_id', null)->latest()->get(),
        );
        return view('admin.products.all_products')->with($data);
    }

    public function add()
    {
        if (!CommonHelpers::rights('product_add')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'Add Product',
            'group_data' => Companies::where('parent_id', null)->latest()->get(),
        );
        return view('admin.products.add_product')->with($data);
    }

    public function get_sub_company(Request $request)
    {
        $company_id = hashids_decode($request->company_id);
        $sub_company = Companies::where('parent_id', $company_id)->get();
        $html = '';
        $html .= '<option value="">Select...</option>';
        if (!empty($sub_company)) {
            foreach ($sub_company as $value) {
                $html .= '<option value="' . $value->hashid . '">' . $value->company_name . '</option>';
            }
        }
        echo $html;
    }

    public function save(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'unit_price' => ['required', 'numeric'],
            'retail' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }
        if (!empty($request->gst)) {
            $validator = \Validator::make($request->all(), [
                'gst' => ['numeric'],
                'gst_amt' => ['numeric'],
            ]);

            if ($validator->fails()) {
                return ['errors' => $validator->errors()];
            }
        }
        if ($request->product_id) {
            $product = Products::hashidFind($request->product_id);
            $companyHashId = Companies::find($product->company_id);
            $msg = 'Product has been updated successfully';
            $redirect = route('admin.products') . '?hashedId=' . urlencode($companyHashId->hash_id);
        } else {
            $product = new Products();
            $msg = 'Product has been added successfully';
            $redirect = route('admin.products');
        }
        $img = null;
        if ($request->img) {
            $img = \CommonHelpers::uploadSingleFile($request->img, 'admin_assets/images/products/');
            if (is_array($img)) {
                $arr = ['status' => 'error', 'msg' => $img['error']];
                return response()->json($arr);
            }
        }

        $product->company_id = hashids_decode($request->group_id);
        if (!empty($request->sub_category)) {
            $product->sub_company_id = hashids_decode($request->sub_category);
        }
        $product->product_code = $request->product_code;
        $product->product_name = $request->product_name;
        $product->packing = $request->packing;
        $product->enlist_code = $request->enlist_code;
        $product->unit_price = $request->unit_price;
        $product->retail_price = $request->retail;
        $product->gst = $request->gst;
        $product->gst_amt = $request->gst_amt;
        if (!empty($request->img)) {
            $product->image = $img;
        }

        $product->save();
        return response()->json([
            'success' => $msg,
            'redirect' => $redirect
        ]);
    }
    public function bonus_status(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:products,id',
            'is_bonus' => 'required|in:0,1',
        ]);

        DB::transaction(function () use ($request) {
            if ($request->is_bonus == 1) {
                // Products::query()->update(['is_active' => 0]);              // deactivate all
                Products::where('id', $request->id)->update(['is_bonus' => 1]); // activate target
            } else {
                Products::where('id', $request->id)->update(['is_bonus' => 0]);
            }
        });

        return response()->json([
            'status'  => true,
            'message' => 'Status updated successfully',
        ]);
    }

    public function list(Request $request)
    { //dd($request->company_id);
        DB::statement(DB::raw('set @rownum=0'));
        $locations = Products::with(['Companies', 'company_group'])->where('company_id', hashids_decode($request->company_id))->latest()->get([
            'products.*',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')
        ]);
        // dd($locations);
        return datatables()->of($locations)
            ->addColumn('img', function ($locations) {
                $img = "<img src='" . check_file($locations->image, 'live_strem') . "' width='20%'>";
                return $img;
            })
            ->addColumn('bonus', function ($locations) {
                $checked = $locations->is_bonus ? 'checked' : '';

                $status = "<div class='custom-control custom-switch'>
                        <input type='checkbox' class='custom-control-input status-switch'
                            id='status_{$locations->id}' data-id='{$locations->id}'
                            {$checked}>
                        <label class='custom-control-label' for='status_{$locations->id}'></label>
                    </div>";
                return $status;
            })
            ->addColumn('action', function ($locations) {
                $button = '';
                if (CommonHelpers::rights('product_edit')) {
                    $button = '<a type="button" name="edit" href="' . route('admin.products.edit', $locations->hashid) . '" class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i class="fas fa-pencil-alt"></i></a>';
                }
                if (CommonHelpers::rights('product_delete')) {
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" onclick="ajaxRequest(this)"  data-url="' . route('admin.products.delete', $locations->hashid) . '"  class="btn btn-outline-danger btn-rounded waves-effect waves-light"><i class="fas fa-trash-alt"></i></button>';
                }
                return $button;
            })
            ->rawColumns(['action', 'img', 'bonus'])
            ->make(true);
    }

    public function edit(Request $request)
    {
        if (!CommonHelpers::rights('product_edit')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'Edit Product',
            'is_edit' => true,
            'group_data' => Companies::where('parent_id', null)->latest()->get(),
            'customer' => Products::with('Companies')->hashidOrFail($request->id)
        );
        $data['sub_company'] = Companies::where('parent_id', $data['customer']->company_id)->latest()->get();
        return view('admin.products.add_product')->with($data);
    }

    public function delete(Request $request)
    {
        if (!CommonHelpers::rights('product_delete')) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        Products::hashidFind($request->id)->delete();
        return response()->json([
            'success' => 'Product Deleted Successfully',
            'reload' => true
        ]);
    }

    public function import_csv_products(Request $request)
    {
        set_time_limit(0);
        if ($request->hasFile('csv_file')) {

            $ext = $request->file('csv_file')->getClientOriginalExtension();

            if (in_array($ext, ['csv', 'CSV'])) {

                $path = $request->file('csv_file')->getRealPath();

                $file = $path;

                $catArr = \CommonHelpers::csvToArray($file);

                $data = array();
                //  dd($catArr); die();
                for ($i = 0; $i < count($catArr); $i++) {

                    if ($catArr[$i]['product_code']) {
                        # code...
                    }
                    $product = Products::where('product_code', '=', $catArr[$i]['product_code'])->first();

                    if ($product == null) {  // echo "string = ";
                        $company_name = $catArr[$i]['company_name'];
                        $sub_company = $catArr[$i]['sub_company'];
                        $company_id = Companies::Where('company_name', $company_name)->Where('parent_id', null)->first();
                        if (empty($company_id)) {
                            $com = new Companies();
                            $com->company_name = $company_name;
                            $com->save();
                            $company_id = $com->id;
                        } else {
                            $company_id = $company_id->id;
                        }
                        $sub_company_id = Companies::Where('company_name', $sub_company)->Where('parent_id', $company_id)->first();
                        if (empty($sub_company_id)) {
                            $sub_com = new Companies();
                            $sub_com->company_name = $sub_company;
                            $sub_com->parent_id = $company_id;
                            $sub_com->save();
                            $sub_company_id = $sub_com->id;
                        } else {
                            $sub_company_id = $sub_company_id->id;
                        }
                        $unit_price = (float) str_replace(',', '', $catArr[$i]['unit_price']);
                        $retail_price = (float) str_replace(',', '', $catArr[$i]['retail_price']);
                        //    if (!empty($group_id)) {
                        $data[$i] = array(
                            'company_id' => $company_id,
                            'sub_company_id' => $sub_company_id,
                            'product_code' => $catArr[$i]['product_code'],
                            'product_name' => $catArr[$i]['product_name'],
                            'packing' => $catArr[$i]['packing'],
                            'enlist_code' => $catArr[$i]['enlist_code'],
                            'unit_price' => $unit_price,
                            'retail_price' => $retail_price,
                            // 'unit_price' =>$catArr[$i]['unit_price'],
                            // 'retail_price' =>$catArr[$i]['retail_price'],
                            'gst' => $catArr[$i]['gst'],
                            'gst_amt' => $catArr[$i]['gst_amt'],
                            "created_at" => date('Y-m-d h:m:s')
                        );
                        // 	}

                    }
                }
                //  exit();
                Products::insert($data);
                return response()->json([
                    'success' => 'CSV Imported Successfully',
                    'redirect' => route('admin.products')
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
    public function import_csv_products_price(Request $request)
    {
        set_time_limit(0);

        if (!$request->hasFile('csv_file')) {
            return response()->json(['error' => 'Select CSV File']);
        }

        $file = $request->file('csv_file');
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext !== 'csv') {
            return response()->json(['error' => 'File type not allowed']);
        }

        $path = $file->getRealPath();
        $catArr = \CommonHelpers::csvToArray($path);

        // Collect all product codes from CSV
        $csvProductCodes = array_filter(array_map(fn($row) => trim($row['product_code'] ?? ''), $catArr));

        if (empty($csvProductCodes)) {
            return response()->json(['error' => 'No product codes found in CSV']);
        }

        // Existing products from DB
        $existingProducts = Products::whereIn('product_code', $csvProductCodes)
            ->get()
            ->keyBy('product_code');

        $count_affected_rows = 0;
        $not_found_product = 0;
        $notFoundProductCodes = []; // ✅ collect missing codes

        foreach ($catArr as $row) {
            $productCode = trim($row['product_code'] ?? '');

            if (empty($productCode)) {
                continue;
            }

            if (isset($existingProducts[$productCode])) {

                $unitPrice   = (float) str_replace(',', '', ($row['unit_price'] ?? 0));
                $retailPrice = (float) str_replace(',', '', ($row['retail_price'] ?? 0));

                $existingProducts[$productCode]->update([
                    'unit_price'   => $unitPrice,
                    'retail_price' => $retailPrice,
                ]);

                $count_affected_rows++;
            } else {
                $not_found_product++;
                $notFoundProductCodes[] = $productCode; // ✅ push missing code
            }
        }

        return response()->json([
            'success' => "$count_affected_rows Products Price Updated Successfully",
            'error'   => $not_found_product
                ? "$not_found_product Products Not Found: " . implode(', ', $notFoundProductCodes)
                : null,
            'not_found_codes' => $notFoundProductCodes, // ✅ optional (useful for frontend)
            // 'redirect' => route('admin.products')
        ]);
    }


    public function company_products()
    {
        $company_id = auth()->user()->company_id;
        $data = array(
            'title' => 'Products',
            'product' => Products::select(DB::raw('products.product_name, SUM(booked_order_details.subtotal) as total_amt'))
                ->join('booked_order_details', 'booked_order_details.product_code', 'products.product_code')
                ->where('company_id', $company_id)
                ->groupBy('products.id')
                ->get()
        );
        return view('admin.products.all_company_product')->with($data);
    }

    //     public function updatingCode(){
    //     // $p = Products::whereRaw('LENGTH(product_code) > ?',[4])->get();
    //     $p = Products::where(DB::raw('LENGTH(product_code)'),'=',5)->get();
    //     // dd($p);
    //     foreach($p AS $key=>$code){

    //         //  Products::where(['product_code',$code->product_code])->update(['product_code'=>'00'.$code->product_code]);
    //         $pc = Products::where('product_code',$code->product_code)->first();
    //         $pc->product_code = '0'.$pc->product_code;
    //         $pc->save();
    //     }
    //     dd('done');
    // }
}
