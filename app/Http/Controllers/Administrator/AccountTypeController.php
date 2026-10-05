<?php

namespace App\Http\Controllers\Administrator;

use App\AccountType;
use App\Branch;
use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;

class AccountTypeController extends AdminController
{
    public function __construct()
    {
        $this->middleware('is_manager_or_admin');
    }

    public function index()
    {
        $account_types = AccountType::with(['branch', 'added_by']);
        if(!auth()->user()->is_admin){
            $account_types = $account_types->whereBranchId(auth()->user()->branch_id);
        }

        $data = array(
            'title' => 'All Account Types',
            'account_types' => $account_types->latest()->get()
        );

        if(auth()->user()->is_admin){
            $data['branches'] = Branch::orderBy('branch_name', 'ASC')->get();
        }
        
        return view('admin.account_types.all_account_types')->with($data);
    }

    public function getSingle(Request $request)
    {
        return response()->json([
            'account_type' => AccountType::hashidFind($request->id)
        ]);
    }

    public function save(Request $request)
    {
        $user = auth()->user();
        //checking if its edit or add
        if ($request->account_type_id) {
            $acc_type = AccountType::hashidFind($request->account_type_id);
            $msg = 'Account Type has been updated successfully';
        } else {
            $acc_type = new AccountType();
            $acc_type->added_by_id = $user->id;
            $acc_type->branch_id = ($user->is_admin) ? hashids_decode($request->branch_id) : $user->branch_id;
            $msg = 'Account Type has been added successfully';
        }

        $acc_type->name = $request->name;
        $acc_type->save();
        
        return response()->json([
            'success' => $msg,
            'redirect' => route('admin.account_types')
        ]);
    }

    public function delete(Request $request)
    {
        AccountType::hashidFind($request->id)->delete();
        return response()->json([
            'success' => 'Account Type Deleted Successfully',
            'reload' => true
        ]);
    }
}
