<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Categories;
use Illuminate\Http\Request;
use CommonHelpers;
use Redirect,
    Response,
    DB,
    Config;

class CategoriesController extends AdminController
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index()
    {
        $data = array(
            'title' => 'All Categories'
        );
        return view('admin.categories.all_categories')->with($data);
    }
    
       public function categories_list()
    {
        DB::statement(DB::raw('set @rownum=0'));   
        $locations = Categories::latest()->get(['categories.*',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return datatables()->of($locations)
                  ->addColumn('action', function($locations){
                        $button = '<a type="button" name="edit" href="'.route('admin.categories.edit', $locations->hashid).'" class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i class="fas fa-pencil-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" onclick="ajaxRequest(this)"  data-url="'.route('admin.categories.delete', $locations->hashid).'"  class="btn btn-outline-danger btn-rounded waves-effect waves-light"><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
            ->make(true);
        
        
    }

  

    public function edit(Request $request)
    {
        $data = array(
            'title' => 'Edit Category',
            'is_edit' => true,
            'category' => Categories::hashidOrFail($request->id),
        );
        return view('admin.categories.all_categories')->with($data);
    }


    public function save(Request $request)
    {
       //checking if its edit or add
        if ($request->category_id) {
            $branch = Categories::hashidFind($request->category_id);
            $msg = 'Category has been updated successfully';
        } else {
            $branch = new Categories();
            $msg = 'Category has been added successfully';
        }

        $branch->category_name = $request->category_name;
      

        $branch->save();
        return response()->json([
            'success' => $msg,
            'redirect' => route('admin.categories')
        ]);
    }

    public function delete(Request $request)
    {
        Categories::hashidFind($request->id)->delete();
        return response()->json([
            'success' => 'Category Deleted Successfully',
            'reload' => true
        ]);
    }
    
     function import_csv_categories(Request $request) {


        if ($request->hasFile('csv_file')) {

            $ext = $request->file('csv_file')->getClientOriginalExtension();

            if (in_array($ext, ['csv', 'CSV'])) {

                $path = $request->file('csv_file')->getRealPath();

                $file = $path;

                $catArr = \CommonHelpers::csvToArray($file);

                $data = array();
                for ($i = 0; $i < count($catArr); $i ++) {
                    $data[$i] = array('category_name' => $catArr[$i]['Category Name'],"created_at" => date('Y-m-d h:m:s'));
                }

                Categories::insert($data);
                return response()->json([
                            'success' => 'CSV Imported Successfully',
                            'redirect' => route('admin.categories')
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
    public function export_categories()
    {
        $data = array(
            'title' => 'Export Category Data',
            'cat_data' => Categories::latest()->get(),
        );
        return view('admin.categories.export_categories')->with($data);
    }
    public function categories_send_email(Request $request)
    {
        $to = $request->email;
        $data['cat_data'] = Categories::latest()->get();
        echo  CommonHelpers::send_email('categories', $data, $to, $subject = 'Categories Report !', $from_email = null, $from_name = null);
    }
}
