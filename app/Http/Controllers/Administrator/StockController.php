<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Stock;
use App\Products;
use App\OrderReturnDetails;
use App\VendorClaimOrderDetails;
use File;
// use Illuminate\Support\Facades\Storage;
use App\BonusPdf;

class StockController extends Controller
{
    public function index()
    {

        $data = array(
            // 'products'  => Stock::with(['parentProduct'])->groupBy('product_id')->get(),
            'products'  => Products::get(),
            'title' => 'Stock'
        );
        return view('admin.stock.index')->with($data);
    }

    public function importStock()
    {
        $data = array(
            'title' => 'Stock Import',
        );
        return view('admin.stock.import')->with($data);
    }

    function import_csv_stock(Request $request)
    {


        if ($request->hasFile('csv_file')) {

            $ext = $request->file('csv_file')->getClientOriginalExtension();

            if (in_array($ext, ['csv', 'CSV'])) {

                $path = $request->file('csv_file')->getRealPath();

                $file = $path;

                $catArr = \CommonHelpers::csvToArray($file);

                foreach ($catArr as $key => $y) {
                    if (strlen($y['product_id']) < 6) {
                        $catArr[$key]['product_id'] = (strlen($y['product_id']) == 4) ? '00' . $y['product_id'] : '0' . $y['product_id'];
                        $date = date('d-m-Y', strtotime(str_replace('/', '-', $y['expiry_date'])));
                        $catArr[$key]['expiry_date'] = date('Y-m-d', strtotime($date));
                    }
                }
                $batchNumbers = array_filter(array_column($catArr, 'batch_no'));
                $existingBatches = Stock::whereIn('batch_no', $batchNumbers)
                    ->pluck('batch_no')
                    ->toArray();
                $seenBatches = [];
                $filteredArr = array_filter($catArr, function ($item) use ($existingBatches, &$seenBatches) {
                    $batchNo = $item['batch_no'] ?? null;

                    // Exclude if batch_no already exists in DB or if it's a duplicate within the CSV itself
                    if (in_array($batchNo, $existingBatches) || isset($seenBatches[$batchNo])) {
                        return false;
                    }

                    if ($batchNo) {
                        $seenBatches[$batchNo] = true;
                    }

                    return true;
                });

                foreach (array_chunk($filteredArr, 1000) as $t) {

                    Stock::insert($t);
                }

                return response()->json([
                    'success' => 'CSV Imported Successfully',
                    'redirect' => route('admin.imports.stock')
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

    public function stockSearch(Request $req)
    {

        if (isset($req->product_id)) {
            if (Products::where('product_code', $req->product_id)->exists()) {
                $data = array(
                    'search'    => TRUE,
                    'products'  => Products::get(),
                    'data'  => Stock::with(['parentProduct'])->where('product_id', $req->product_id)->get(),
                    'product_name'   => Products::get()->keyBy('id'),
                    // 'data'  => Stock::with(['parentProduct'])->where('product_id',hashids_decode($req->product_id))->get(),
                    'title' => 'Stock',
                );
                // dd($data['data']);
                return view('admin.stock.index')->with($data);
            }
            return redirect()->back();
        }
    }

    public function editImportStockField(Request $req)
    {

        if (isset($req->id) && !empty($req->id)) {
            if (Stock::where('id', hashids_decode($req->id))->exists()) {

                $stock_detail = Stock::with(['parentProduct'])->where('id', hashids_decode($req->id))->first();
                $product_detail = Products::where('id', intVal($stock_detail->product_id))->first(); //doing this because in stock table product_id is not in actual format
                $html = view('admin.stock.edit_stock_field')->with(compact('stock_detail', 'product_detail'))->render();

                return response()->json([
                    'html'  => $html,
                ]);
            }
        }
    }

    public function updateImportStockField(Request $req)
    {
        if (isset($req->stock_id) && !empty($req->stock_id)) {
            if (Stock::where('id', hashids_decode($req->stock_id))->exists()) {

                $update = Stock::hashidFind($req->stock_id);
                $update->batch_no = $req->batch_no;
                $update->expiry_date = $req->expiry_date;
                $update->qty = $req->qty;
                $update->rate = $req->rate;
                $update->save();

                return response()->json([
                    'success'   => 'Stock udpated succesfully',
                    'reload'    => TRUE,
                ]);
            }
        }
    }

    public function updateDateFormat()
    {

        $stocks = VendorClaimOrderDetails::get();
        $stocks_arr = array();
        $count = 0;
        $not_count = 0;

        foreach ($stocks as $key => $arr) {

            $stock = VendorClaimOrderDetails::find($arr->id);
            if ($stock) {
                ++$count;
                $m = date('d', strtotime($stock->expiry_date));
                $d = date('m', strtotime($stock->expiry_date));
                $y = date('Y', strtotime($stock->expiry_date));
                //creating new date
                $new_date = $y . '-' . $m . '-' . $d;
                $date_create = date_create($new_date);

                $stock->expiry_date = $date_create;
                $stock->save();
            } else {
                ++$not_count;
            }
        }
        echo "count:$count <br>";
        echo "not count:$not_count";
        die();
    }

    public function uploadBonusPdf()
    {
        $data = array(
            'title' => 'Upload Bonus PDF',
            'pdfs'  => BonusPdf::get(),
        );
        return view('admin.bonus.upload_bonus_pdf')->with($data);
    }

    function storeBonusPdf(Request $request)
    {

        if ($request->hasFile('pdf_file')) {

            $ext = $request->file('pdf_file')->getClientOriginalExtension();

            if (in_array($ext, ['pdf', 'PDF'])) {
                if (BonusPdf::where('file_name', $request->file_name)->doesntExist()) {
                    //if bonus_pdf folder does not exists then create one
                    if (!File::exists(public_path() . "/bonus_pdf")) {
                        File::makeDirectory(public_path() . "/bonus_pdf");
                    }
                    //set file name and file path
                    $file_name = $request->file_name . "." . $ext;
                    $file_path = "bonus_pdf/" . $file_name;

                    $request->file('pdf_file')->storeAs('bonus_pdf', $file_name);

                    //store file data
                    $bonus = new BonusPdf;
                    $bonus->file_name = $request->file_name;
                    $bonus->file_path = $file_path;
                    $bonus->save();

                    return response()->json([
                        'success' => 'PDF Uploaded Successfully',
                        'redirect' => route('admin.uploads.bonus.pdf')
                    ]);
                } else {
                    return response()->json([
                        'error' => "File Name Already Exists",
                    ]);
                }
            } else {
                return response()->json([
                    'error' => 'file type not allowed'
                ]);
            }
            return response()->json([
                'error' => 'Select PDF File'
            ]);
        }
    }
}
