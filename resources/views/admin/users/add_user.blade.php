@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                    <li class="breadcrumb-item active">{{@$is_edit ? 'Edit' : 'Add New'}} User</li>
                </ol>
            </div>
            <h4 class="page-title">{{@$is_edit ? 'Edit' : 'New'}} User</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">{{@$is_edit ? 'Edit' : 'Add'}} New User</h4>
            <p class="text-muted font-14 m-b-20">
                {{@$is_edit ? 'Edit' : 'Add'}} staff users.
            </p>
            <form action="{{ route('admin.users.save') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                @if(isset($branches) && newCount($branches) > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="branch_id">Select Branch</label>
                            <select id="branch_id" name="branch_id" class="form-control">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                <option {{ isset($user) && $user->branch_id == $branch->id ? 'selected' : ''}} value="{{ $branch->hashid }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Full Name<span class="text-danger">*</span></label>
                            <input type="text" id="firstname" name="firstname" parsley-trigger="change" placeholder="Enter First Name" class="form-control" value="{{ @$user->firstname ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">Email @if(!isset($user))<span class="text-danger">*</span>@endif</label>
                            @if(isset($user))
                            <input type="text" id="email" class="form-control" disabled value="{{ $user->email }}">
                            @else
                            <input type="email" id="email" name="email" parsley-trigger="change" placeholder="Enter Email" class="form-control" required>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">

                    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">Password @if(!isset($user))<span class="text-danger">*</span>@endif</label>
                            <input type="password" id="password" name="password" parsley-trigger="change" placeholder="Enter Password" class="form-control" minlength="6" {{ isset($user) ? '' : 'required' }}>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="user_role">User Role<span class="text-danger">*</span></label>
                            <select onchange="adminRoleRights(this.value)" id="user_role" name="user_role" parsley-trigger="change" class="form-control" required>
                                <option value="">Select User Role</option>
                                @if(auth()->user()->is_admin)
                                <option {{ isset($user) && $user->user_role == 'admin' ? 'selected' : ''}} value="admin">Admin</option> 
                                @endif
                                <option {{ isset($user) && $user->user_role == 'user' ? 'selected' : ''}} value="user">User</option>
                                <option {{ isset($user) && $user->user_role == 'company' ? 'selected' : ''}} value="company">Company</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6" id="company_filed" style="{{ isset($user) && $user->user_role == 'company' ? '' : 'display:none'}};">
                        <div class="form-group">
                            <label for="user_role">Company<span class="text-danger">*</span></label>
                            <select id="company_id" name="company_id" parsley-trigger="change" class="form-control">
                                <option value="">Select Company</option>
                                @foreach($company_data as $company_data)
                                <option value="{{$company_data->hashid}}" {{ isset($user) && $user->company_id == $company_data->id ? 'selected' : ''}}>{{$company_data->company_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" id="rights">
                    <div class="col-sm-12">
                        <h5 for="Rights">User Rights</h5>
                    </div>    
                   
                    <div class="col-sm-6">
                        <div class="form-group" >
                            <label for="Rights">Routs</label>  
                            <table class="dt_table table-bordered w-100 nowrap responsive">
                                <thead>
                                    <tr style="text-align:center;">
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align:center;">
                                        <td><input type="checkbox" class="form-control" name="routs_view"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->routs_view)?'checked': '' }} /></td>
                                        <td><input type="checkbox" class="form-control" name="routs_add"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->routs_add)?'checked': '' }} /></td>
                                        <td><input type="checkbox" class="form-control" name="routs_edit"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->routs_edit)?'checked': '' }} /></td>
                                        <td><input type="checkbox" class="form-control" name="routs_delete"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->routs_delete)?'checked': '' }} /></td>
                                    </tr>
                                </tbody>
                            </table>
                      
                        </div>
                        

                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" id="rights">
                            <label for="Rights">Towns</label>
                            <table class="dt_table table-bordered w-100 nowrap responsive">
                                <thead>
                                    <tr style="text-align:center;">
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align:center;">
                                        <td>
                                            <input type="checkbox" class="form-control" name="town_view"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->town_view)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="town_add"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->town_add)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="town_edit"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->town_edit)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="town_delete"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->town_delete)?'checked': '' }} />
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" id="rights">
                            
                             <label for="Rights">Company</label> 
                            <table class="dt_table table-bordered w-100 nowrap responsive">
                                <thead>
                                    <tr style="text-align:center;">
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align:center;">
                                        <td>
                                            <input type="checkbox" class="form-control" name="company_view"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->company_view)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="company_add"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->company_add)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="company_edit"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->company_edit)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="company_delete"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->company_delete)?'checked': '' }} /> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" id="rights">
                            <label for="Rights">Customer</label>
                            <table class="dt_table table-bordered w-100 nowrap responsive">
                                <thead>
                                    <tr style="text-align:center;">
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align:center;">
                                        <td>
                                            <input type="checkbox" class="form-control" name="customer_view"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->customer_view)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="customer_add"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->customer_add)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="customer_edit"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->customer_edit)?'checked': '' }} />
                                        </td>
                                        <td>
                                           <!--  <input type="checkbox" class="form-control" name="customer_delete"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->customer_delete)?'checked': '' }} /> --> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" id="rights">
                            <label for="Rights">Products</label>
                            <table class="dt_table table-bordered w-100 nowrap responsive">
                                <thead>
                                    <tr style="text-align:center;">
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align:center;">
                                        <td>
                                            <input type="checkbox" class="form-control" name="product_view"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->product_view)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="product_add"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->product_add)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="product_edit"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->product_edit)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="product_delete"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->product_delete)?'checked': '' }} /> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" id="rights">
                            <label for="Rights">Groups</label>
                            <table class="dt_table table-bordered w-100 nowrap responsive">
                                <thead>
                                    <tr style="text-align:center;">
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align:center;">
                                        <td>
                                            <input type="checkbox" class="form-control" name="group_view"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->group_view)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="group_add"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->group_add)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="group_edit"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->group_edit)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="group_delete"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->group_delete)?'checked': '' }} /> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>  
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" id="rights">
                            <label for="Rights">Employees</label>
                            <table class="dt_table table-bordered w-100 nowrap responsive">
                                <thead>
                                    <tr style="text-align:center;">
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align:center;">
                                        <td>
                                            <input type="checkbox" class="form-control" name="employees_view"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->employees_view)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="employees_add"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->employees_add)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="employees_edit"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->employees_edit)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="employees_delete"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->employees_delete)?'checked': '' }} /> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>   
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" id="rights">
                            <label for="Rights">Task</label> 
                            <table class="dt_table table-bordered w-100 nowrap responsive">
                                <thead>
                                    <tr style="text-align:center;">
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align:center;">
                                        <td>
                                            <input type="checkbox" class="form-control" name="task_view"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->task_view)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="task_add"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->task_add)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="task_edit"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->task_edit)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="task_delete"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->task_delete)?'checked': '' }} /> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group" id="rights">
                            <label for="Rights">Orders</label> 
                            <table class="dt_table table-bordered w-100 nowrap responsive">
                                <thead>
                                    <tr style="text-align:center;">
                                        <th>View</th>
                                        <th>Update</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align:center;">
                                        <td>
                                            <input type="checkbox" class="form-control" name="orders_view"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->orders_view)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="orders_update"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->orders_update)?'checked': '' }} />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                     <div class="col-sm-6">
                        <div class="form-group" id="rights">
                            <label for="Rights">Orders Return</label> 
                            <table class="dt_table table-bordered w-100 nowrap responsive">
                                <thead>
                                    <tr style="text-align:center;">
                                        <th>View</th>
                                        <th>Update</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align:center;">
                                        <td>
                                            <input type="checkbox" class="form-control" name="orders_return_view"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->orders_return_view)?'checked': '' }} />
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-control" name="orders_return_update"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="1" {{ @($user->rights->orders_return_update)?'checked': '' }} />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                   <div class="form-group mb-3 text-right">
                            @if(isset($user))
                            <input type="hidden" value="{{ $user->hashid }}" name="user_id" />
                            @endif
                            <button class="btn btn-primary waves-effect waves-light" type="submit">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                                Cancel
                            </button>
                        </div>

                        </form>
                    </div>
                </div>
        </div>
        @endsection


        @section('page-scripts')
        @include('admin.partials.datatable', ['load_swtichery' => true])
        <script>
            function adminRoleRights(val) {
                if (val == 'company') {
                     $('#company_filed').show();
                     $("#company_id").prop('required',true);
                 }else{
                    $('#company_filed').hide();
                    $("#company_id").prop('required',false);
                 }
                if (val == 'admin' || val == 'company') {
                    $('#rights').hide()
                } else {
                    $('#rights').show()
                }
            }
        </script>
        @endsection