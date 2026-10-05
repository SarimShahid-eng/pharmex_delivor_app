<?php

namespace App\Http\Controllers\Administrator;

use App\Categories;
use App\Items;
use App\Stocks;
use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;
use Redirect,
    Response,
    DB,
    Config;
use CommonHelpers;

class ItemsController extends AdminController {

    public function __construct() {
        
    }

    public function index() {
        if (!CommonHelpers::rights('items_View') || auth()->user()->is_admin) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }


        $data = array(
            'title' => 'All Items',
        );
        return view('admin.item.all_items')->with($data);
    }

    public function add() {
       if (!auth()->user()->is_admin) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'Add New Cargo Service',
            'cat_data' => Categories::latest()->get(),
        );

        return view('admin.item.add_item')->with($data);
    }

    public function edit(Request $request) {
        if (!auth()->user()->is_admin) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'Edit Item',
            'is_edit' => true,
            'item_data' => Items::hashidOrFail($request->id),
            'cat_data' => Categories::latest()->get(),
        );
        return view('admin.item.add_item')->with($data);
    }

    public function save(Request $request) {
       if (!auth()->user()->is_admin) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }



        if ($request->item_id) {
            $Item = Items::hashidFind($request->item_id);
            if ($Item->item_code != $request->item_code) {

                $this->validate($request, [
                    'item_code' => 'required|unique:Items,item_code',
                ]);
            }

            $msg = 'Item has been updated successfully';
        } else {
            $Item = new Items();
            $this->validate($request, [
                'item_code' => 'required|unique:Items,item_code',
            ]);
            $msg = 'Item has been added successfully';
        }
        if ($request->stock_in) {
            $taxable = "yes";
        } else {
            $taxable = "no";
        }

        $Item->categories_id = hashids_decode($request->categories_id);
        $Item->item_code = $request->item_code;
        $Item->item_name = $request->item_name;
        $Item->description = $request->description;
        $Item->unit_name = $request->unit_name;
        $Item->unit_size = $request->unit_size;
        $Item->ctn_size = $request->ctn_size;
        $Item->unit_cost = $request->unit_cost;
        $Item->ctn_cost = $request->ctn_cost;
        $Item->par_level = $request->par_levels;
        $Item->taxable = $taxable;
        $Item->groups = $request->group;
        $Item->save();
        return response()->json([
                    'success' => $msg,
                    'redirect' => route('admin.item')
        ]);
    }

    public function items_list() {
        DB::statement(DB::raw('set @rownum=0'));
        $locations = DB::select("SELECT items.*,@rownum  := @rownum  + 1 AS rownum, categories.category_name, 
                            (SELECT SUM(qty) FROM stocks WHERE stocks.item_id = items.id AND stocks.type = 'in' AND items.deleted_at is null) 'stock_in',
                            (SELECT SUM(qty) FROM stocks WHERE stocks.item_id = items.id AND stocks.type = 'out' AND items.deleted_at is null) 'stock_out'
                             FROM items
                             left JOIN categories ON categories.id = items.categories_id where items.deleted_at is null");
        return datatables()->of($locations)
                        ->addColumn('items_code', function($locations) {
                            $view_modal = '<a href="#" class="details_btn" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="' . hashids_encode($locations->id) . '">' . $locations->item_code . '</a>';

                            return $view_modal;
                        })
                        ->addColumn('items_name', function($locations) {
                            return wordwrap($locations->item_name, 25, "<br>\n");
                        })
                        ->addColumn('stock_levels', function($locations) {
                            $stock = $locations->stock_in - $locations->stock_out;
                            if ($stock < $locations->par_level) {
                                $stock_level = 'Low';
                            } else {
                                $stock_level = 'High';
                            }
                            return $stock_level;
                        })
                        ->addColumn('action', function($locations) {
                            // $button = '<button type="button" name="edit" onclick="ajaxRequest(this)" data-url="' . route('admin.item.delete', hashids_encode($locations->id)) . '" class="btn btn-outline-danger btn-rounded waves-effect waves-light"><i class="fas fa-trash-alt"></i></button>&nbsp;&nbsp;&nbsp;';

                            $button = '<a type="button" name="edit" href="' . route('admin.item.edit', hashids_encode($locations->id)) . '" class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i class="fas fa-pencil-alt"></i></a>';
                            return $button;
                        })->addColumn('status', function($locations) {
                            $checked = ($locations->status) ? 'checked' : '';
                            $btn = '<p class="m-0 text-center">';
                            $btn .= '<input type="checkbox" class="nopopup" onchange="ajaxRequest(this)" ' . $checked . '   data-url="' . route('admin.item.change_status', hashids_encode($locations->id)) . '" data-toggle="switchery" data-size="small" data-color="#1bb99a"/></p>';

                            return $btn;
                        })
                        ->rawColumns(['action', 'items_code', 'items_name', 'stock_levels', 'status'])
                        ->make(true);
    }

    public function change_status(Request $request) {

        $pallets = Items::hashidFind($request->id);
        $pallets->status = (!$pallets->status) ? '1' : '0';
        $pallets->save();
        return response()->json([
                    'success' => 'User Status Updated Successfully',
        ]);
    }

    public function delete(Request $request) {
       if (!auth()->user()->is_admin) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        Items::hashidFind($request->id)->delete();
        return response()->json([
                    'success' => 'Item Deleted Successfully',
                    'reload' => true
        ]);
    }

    public function model_data(Request $request) {
        $items = Items::where('id', hashids_decode($request->id))->get();
        echo json_encode(array('items' => $items));
        exit;
    }

    function import_csv_item(Request $request) {


        if ($request->hasFile('csv_file')) {

            $ext = $request->file('csv_file')->getClientOriginalExtension();

            if (in_array($ext, ['csv', 'CSV'])) {

                $path = $request->file('csv_file')->getRealPath();

                $file = $path;

                $itemArr = \CommonHelpers::csvToArray($file);



                $data = array();
                for ($i = 0; $i < count($itemArr); $i ++) {

                    $cat_name = strtolower(@$itemArr[$i]["Category Name"]);
                    $cat_arr = Categories::where('category_name', $cat_name)->first();
                    $cat_id = $cat_arr->id;
                    $item_code = @$itemArr[$i]["Item Code"];
                    $check = Items::where("item_code", $item_code)->get();

                    if (count($check) > 0) {

                        $arr = array(
                            "categories_id" => $cat_id,
                            "item_name" => @$itemArr[$i]["Item Name"],
                            "unit_name" => @$itemArr[$i]["Unit Name"],
                            "unit_size" => @$itemArr[$i]["Unit Size"],
                            "unit_cost" => @$itemArr[$i]["Unit Cost"],
                            "ctn_size" => @$itemArr[$i]["CTN Size"],
                            "ctn_cost" => (@$itemArr[$i]["Unit Cost"] * @$itemArr[$i]["CTN Size"]),
                            "updated_at" => date('Y-m-d h:m:s'),
                            "par_level" => @$itemArr[$i]["Par Level"],
                            "description" => @$itemArr[$i]["description"],
                            "taxable" => @$itemArr[$i]["taxable"],
                            "groups" => @$itemArr[$i]["groups"]
                        );

                        Items::where("item_code", $item_code)->update($arr);
                    } else {
                        $data[$i] = array(
                            "categories_id" => $cat_id,
                            "item_code" => @$itemArr[$i]["Item Code"],
                            "item_name" => @$itemArr[$i]["Item Name"],
                            "unit_name" => @$itemArr[$i]["Unit Name"],
                            "unit_size" => @$itemArr[$i]["Unit Size"],
                            "unit_cost" => @$itemArr[$i]["Unit Cost"],
                            "ctn_size" => @$itemArr[$i]["CTN Size"],
                            "ctn_cost" => (@$itemArr[$i]["Unit Cost"] * @$itemArr[$i]["CTN Size"]),
                            "created_at" => date('Y-m-d h:m:s'),
                            "par_level" => @$itemArr[$i]["Par Level"],
                            "description" => @$itemArr[$i]["description"],
                            "taxable" => @$itemArr[$i]["taxable"],
                            "groups" => @$itemArr[$i]["groups"]
                        );
                    }
                }

                Items::insert($data);
                return response()->json([
                            'success' => 'CSV Imported Successfully',
                            'redirect' => route('admin.item')
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

    public function export_item($value = '') {
        if (!CommonHelpers::rights('items_View') || auth()->user()->is_admin) {
            if (!$this->authorize('create', 'App\Admin')) {
                abort('403', env('ERROR_403'));
            }
        }
        $data = array(
            'title' => 'All Items',
            'item_data' => DB::select("SELECT items.*, categories.category_name, 
                            (SELECT SUM(qty) FROM stocks WHERE stocks.item_id = items.id AND stocks.type = 'in' AND `stocks`.`deleted_at` IS NULL) 'stock_in',
                            (SELECT SUM(qty) FROM stocks WHERE stocks.item_id = items.id AND stocks.type = 'out' AND `stocks`.`deleted_at` IS NULL) 'stock_out'
                             FROM items
                             left JOIN categories ON categories.id = items.categories_id where items.deleted_at is null"),
        );
        return view('admin.item.export_item')->with($data);
    }

    public function item_send_email(Request $request) {
        $to = $request->email;
        $data['item_rec'] = DB::select("SELECT items.*, categories.category_name, 
                            (SELECT SUM(qty) FROM stocks WHERE stocks.item_id = items.id AND stocks.type = 'in' AND `stocks`.`deleted_at` IS NULL) 'stock_in',
                            (SELECT SUM(qty) FROM stocks WHERE stocks.item_id = items.id AND stocks.type = 'out' AND `stocks`.`deleted_at` IS NULL) 'stock_out'
                             FROM items
                             left JOIN categories ON categories.id = items.categories_id where items.deleted_at is null");
        echo CommonHelpers::send_email('item', $data, $to, $subject = 'Items Report !', $from_email = null, $from_name = null);
    }

}
