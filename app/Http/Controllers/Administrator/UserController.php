<?php

namespace App\Http\Controllers\Administrator;

use App\Admin as User;
use App\Branch;
use App\Locations;
use App\PalletsQty;
use App\Site;
use App\Companies;
use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;

class UserController extends AdminController {

    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index() {
        if (!$this->authorize('create', 'App\Admin')) {
            abort('403', env('ERROR_403'));
        }

        $users = User::with(['added_by'])->whereNotIn('id', ['1', auth()->user()->id])->latest();


        $data = array(
            'title' => 'All Users',
            'users' => $users->latest()->get(),
        );
        return view('admin.users.all_users')->with($data);
    }

    public function add() {
        if (!$this->authorize('create', 'App\Admin')) {
            abort('403', env('ERROR_403'));
        }

        $data = array(
            'title' => 'Add New user',
            'company_data' => Companies::where('parent_id',null)->latest()->get(),
        );


        return view('admin.users.add_user')->with($data);
    }

    public function edit(Request $request) {
        $user = User::hashidOrFail($request->id);

        if (!$this->authorize('update', $user)) {
            abort('403', env('ERROR_403'));
        }

        $data = array(
            'title' => 'Edit user',
            'is_edit' => true,
            'user' => $user,
            'company_data' => Companies::where('parent_id',null)->latest()->get(),
        );


        return view('admin.users.add_user')->with($data);
    }

    public function save(Request $request) {
        $rules = array(
            'firstname' => ['required', 'string'],
            'user_role' => ['required', 'string'],
        );

       if (!$request->user_id) {
            $rules['email'] = ['required', 'unique:admins,email,NULL,id,deleted_at,NULL'];
        }

        if (!$request->user_id || $request->password != '') {
            $rules['password'] = ['required', 'string', 'min:6'];
        }

        $validator = \Validator::make($request->toArray(), $rules);

        if (!$validator->passes()) {
            return response()->json([
                        'errors' => $validator->errors(),
            ]);
        }


        $rights = [];
        if ($request->user_role == 'user') {

            if ($request->routs_view) {
                $rights['routs_view'] = 1;
            } else {
                $rights['routs_view'] = 0;
            }

            if ($request->routs_add) {
                $rights['routs_add'] = 1;
            } else {
                $rights['routs_add'] = 0;
            }

            if ($request->routs_edit) {
                $rights['routs_edit'] = 1;
            } else {
                $rights['routs_edit'] = 0;
            }

            if ($request->routs_delete) {
                $rights['routs_delete'] = 1;
            } else {
                $rights['routs_delete'] = 0;
            }
            
            if ($request->town_view) {
                $rights['town_view'] = 1;
            } else {
                $rights['town_view'] = 0;
            }
            if ($request->town_add) {
                $rights['town_add'] = 1;
            } else {
                $rights['town_add'] = 0;
            }
            if ($request->town_edit) {
                $rights['town_edit'] = 1;
            } else {
                $rights['town_edit'] = 0;
            }
            if ($request->town_delete) {
                $rights['town_delete'] = 1;
            } else {
                $rights['town_delete'] = 0;
            }
            if ($request->company_view) {
                $rights['company_view'] = 1;
            } else {
                $rights['company_view'] = 0;
            }
            if ($request->company_add) {
                $rights['company_add'] = 1;
            } else {
                $rights['company_add'] = 0;
            }
            if ($request->company_edit) {
                $rights['company_edit'] = 1;
            } else {
                $rights['company_edit'] = 0;
            }
            if ($request->company_delete) {
                $rights['company_delete'] = 1;
            } else {
                $rights['company_delete'] = 0;
            }
            if ($request->customer_view) {
                $rights['customer_view'] = 1;
            } else {
                $rights['customer_view'] = 0;
            }
            if ($request->customer_add) {
                $rights['customer_add'] = 1;
            } else {
                $rights['customer_add'] = 0;
            }
            if ($request->customer_edit) {
                $rights['customer_edit'] = 1;
            } else {
                $rights['customer_edit'] = 0;
            }
            // if ($request->customer_delete) {
            //     $rights['customer_delete'] = 1;
            // } else {
            //     $rights['customer_delete'] = 0;
            // }
            if ($request->product_view) {
                $rights['product_view'] = 1;
            } else {
                $rights['product_view'] = 0;
            }
            if ($request->product_add) {
                $rights['product_add'] = 1;
            } else {
                $rights['product_add'] = 0;
            }
            if ($request->product_edit) {
                $rights['product_edit'] = 1;
            } else {
                $rights['product_edit'] = 0;
            }
            if ($request->product_delete) {
                $rights['product_delete'] = 1;
            } else {
                $rights['product_delete'] = 0;
            }
            if ($request->group_view) {
                $rights['group_view'] = 1;
            } else {
                $rights['group_view'] = 0;
            }
            if ($request->group_add) {
                $rights['group_add'] = 1;
            } else {
                $rights['group_add'] = 0;
            }
            if ($request->group_edit) {
                $rights['group_edit'] = 1;
            } else {
                $rights['group_edit'] = 0;
            }
            if ($request->group_delete) {
                $rights['group_delete'] = 1;
            } else {
                $rights['group_delete'] = 0;
            }
            if ($request->employees_view) {
                $rights['employees_view'] = 1;
            } else {
                $rights['employees_view'] = 0;
            }
            if ($request->employees_add) {
                $rights['employees_add'] = 1;
            } else {
                $rights['employees_add'] = 0;
            }
            if ($request->employees_edit) {
                $rights['employees_edit'] = 1;
            } else {
                $rights['employees_edit'] = 0;
            }
            if ($request->employees_delete) {
                $rights['employees_delete'] = 1;
            } else {
                $rights['employees_delete'] = 0;
            }
            if ($request->task_view) {
                $rights['task_view'] = 1;
            } else {
                $rights['task_view'] = 0;
            }
            if ($request->task_add) {
                $rights['task_add'] = 1;
            } else {
                $rights['task_add'] = 0;
            }
            if ($request->task_edit) {
                $rights['task_edit'] = 1;
            } else {
                $rights['task_edit'] = 0;
            }
            if ($request->task_delete) {
                $rights['task_delete'] = 1;
            } else {
                $rights['task_delete'] = 0;
            }
            if ($request->orders_view) {
                $rights['orders_view'] = 1;
            } else {
                $rights['orders_view'] = 0;
            }
            if ($request->orders_update) {
                $rights['orders_update'] = 1;
            } else {
                $rights['orders_update'] = 0;
            }
            if ($request->orders_return_view) {
                $rights['orders_return_view'] = 1;
            } else {
                $rights['orders_return_view'] = 0;
            }
            if ($request->orders_return_update) {
                $rights['orders_return_update'] = 1;
            } else {
                $rights['orders_return_update'] = 0;
            }
        }



        if (!$request->user_id) {
            $user = new User();
            $user->email = $request->email;
            $user->added_by_id = auth()->user()->id;
            $user->password = \Hash::make($request->password);
            $msg = 'User has been added successfully';
        } else {
            $user = User::hashidFind($request->user_id);
            $user->password = ($request->password && !empty($request->password)) ? \Hash::make($user->password) : $user->password;
            $msg = 'User has been updated successfully';
        }

        if (auth()->user()->is_admin) {


            $user_role = $request->user_role;
        } else {

            $user_role = $request->user_role == 'admin' ? $user->user_role : $request->user_role;
        }

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        // $user->branch_id = ($request->user_role !== 'admin') ? $branch_id : null;
        $user->user_role = $user_role;

        $user->rights = ($request->user_role == 'user') ? $rights : NULL;
        if ($request->user_role == 'company') {
            $user->company_id = hashids_decode($request->company_id);
        }
        $user->save();

        return response()->json([
                    'success' => $msg,
                    'redirect' => route('admin.users')
        ]);
    }

    public function delete(Request $request) {
        User::hashidFind($request->id)->delete();
        return response()->json([
                    'success' => 'User Deleted Successfully',
                    'reload' => true
        ]);
    }

    public function change_status(Request $request) {
        $user = User::hashidFind($request->id);
        $user->is_active = !$user->is_active;
        $user->save();
        return response()->json([
                    'success' => 'User Status Updated Successfully',
        ]);
    }

    public function change_profile() {
        
        $data = array(
            'title' => 'Your Profile',
            // 'site' => Site::orderByDesc('id')->first(),
            //'pallet_qty' => PalletsQty::first(),
        );
        return view('admin.users.user_profile')->with($data);
    }

    public function update_password(Request $request) {
        $messages = [
            'current_password.required' => 'Please enter current password',
            'password.required' => 'Please enter new password',
        ];

        $validator = \Validator::make($request->all(), [
                    'current_password' => ['required'],
                    'new_password' => ['required', 'string', 'same:new_password', 'min:6'],
                    'confirm_password' => ['required', 'string', 'same:new_password', 'min:6'],
                        ], $messages);


        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        if (\Hash::check($request->current_password, auth()->user()->password)) {
            $user = User::find(auth()->user()->id);
            $user->password = \Hash::make($request->new_password);
            $user->save();
            // auth()->logoutOtherDevices($request->new_password);
            return response()->json([
                        'success' => "Your password have been changed",
                        'reload' => 'true'
            ]);
        }


        return response()->json([
                    'error' => "You have entered a wrong password!",
        ]);
    }

    public function update_profile(Request $request) {
        $user = User::find(auth()->user()->id);

        if (!$user) {
            return response()->json([
                        'error' => "User Not Found!",
            ]);
        }

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;

        if ($request->hasFile('profile_pic')) {
            $image = \CommonHelpers::uploadSingleFile($request->file('profile_pic'), 'uploads/profile_pic/');
            if (is_array($image)) {
                return response()->json($image);
            }

            if ($request->category_id) {
                unlink($user->image);
            }
            $user->image = $image;
        }

        $user->save();

        return response()->json([
                    'success' => "Your profile has been updated",
                    'reload' => 'true'
        ]);
    }

    public function pallet_qty(Request $request) {
        $plletsQty = PalletsQty::hashidFind($request->pallet_qty_id);
        $plletsQty->qty = $request->pallet_qty;
        $plletsQty->save();
        return response()->json([
                    'success' => 'pallet Quantity Updated Successfully',
                    'reload' => 'true'
        ]);
    }

    public function site_update(Request $request) {
      
        $sites = new Site();
        foreach ($request->site_id as $key => $value) {
            $Site = $sites->hashidFind($request->site_id[$key]);

            $old_site = $Site->site_name;
            $new_site = $request->site_name[$key];
            $locations = Locations::where('site', $old_site)->get();


            foreach ($locations as $ky => $val) {

                $val->site = $new_site;
                $val->save();
            }

            $Site->site_name = $new_site;
            $Site->save();
        }

        return response()->json([
                    'success' => 'Site Updated Successfully',
                    'reload' => 'true'
        ]);
    }

    //update site name
    public function update_site_name(Request $req){
        $site = new Site;
        $site->site_name = $req->site_name;
        $site->save();
       
        return redirect()->route('admin.change_password');
    }

}
