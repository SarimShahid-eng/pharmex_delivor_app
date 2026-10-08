<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolicyController extends Controller
{
    public function index()
    {
        return view('admin.policies.index', [
            'title'    => 'Policies',
            'policies' => Policy::latest()->get(),
        ]);
    }
    public function create()
    {
        return view('admin.policies.create');
    }
    public function edit($id)
    {
        $policy = Policy::findOrFail($id);

        return view('admin.policies.create', [
            'is_edit' => true,
            'policy'  => $policy,
        ]);
    }


    public function save(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'policies'   => 'required|array|min:1',
            'policies.*' => 'required|string',
        ]);

        // update_id present = update, otherwise create
        $policy = $request->filled('update_id')
            ? Policy::findOrFail($request->update_id)
            : new Policy;

        $policy->title    = $request->title;
        $policy->policies = array_values(array_filter($request->policies));
        $policy->save();
        $msg = $request->filled('update_id') ? 'Policy updated successfully' : 'Policy saved successfully';
        $redirect = route('admin.policies.index');
        return response()->json([
            'success' => $msg,
            'redirect' => $redirect
        ]);
    }
    public function status(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:policies,id',
            'status' => 'required|in:0,1',
        ]);

        DB::transaction(function () use ($request) {
            if ($request->status == 1) {
                Policy::query()->update(['is_active' => 0]);              // deactivate all
                Policy::where('id', $request->id)->update(['is_active' => 1]); // activate target
            } else {
                Policy::where('id', $request->id)->update(['is_active' => 0]);
            }
        });

        return response()->json([
            'status'  => true,
            'message' => 'Status updated successfully',
        ]);
    }
    public function delete($id)
    {
        Policy::findOrFail($id)->delete();   // permanent delete

        return response()->json([
            'success' => 'Policy Deleted Successfully',
            'reload' => true
        ]);
    }
}
