@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Employee</a></li>
                    <li class="breadcrumb-item active">{{@$is_edit ? 'Edit' : 'Add New'}} Employee</li>
                </ol>
            </div>
            <h4 class="page-title">{{@$is_edit ? 'Edit' : 'New'}} Employee</h4>
        </div>
    </div>
</div>
<!-- <div class="row">
    <div class="col-lg-12">
        <div class="card custom_filters">
            <div class="card-header bg-dark text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#filters_div" role="button" aria-controls="filters_div" class=""><i class="mdi mdi-plus"></i></a>
                </div>
                <h4 class="card-title mb-0 text-white">Employee <small>(Import CSV File)</small></h4>
            </div>
            <div id="filters_div" class="card-body collapse show ">
                <form action="{{ route('admin.employee.import') }}" method="post" class="row align-items-end justify-content-center mb-2 ajaxForm" enctype='multipart/form-data'>
                     @csrf
                     <div class="col-12">
                        <div class="row">
                           <div class="col-sm-4 text-center offset-sm-3">
                               <label for="order_no" class="sr-onlys mr-1">Employee CSV</label>
                               <input type="file" name="csv_file" accept=".csv,.CSV" id="csv" class="form-control-file" required="">
                               <a href="{{ asset('uploads/csvsheet/employee.csv') }}" class="d-block mt-3">Employee Format</a>
                                <span><b>Note</b>: Use only for additional new entries, or use edit to change the names of existing names</span>

                           </div>

                            <div class="col-sm-2 ">
                                <button type="submit" class="btn btn-primary waves-effect waves-light" style="margin-top: 24px;">Import</button>
                           </div>
                        </div>
                     </div>
                </form>

            </div>
        </div>
    </div>
</div> -->
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">{{@$is_edit ? 'Edit' : 'Add New'}} Employee</h4> <br>
            <form action="{{ route('admin.employees.save') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Employee Name<span class="text-danger">*</span></label>
                            <input type="text" id="firstname" name="employee_name" parsley-trigger="change" placeholder="Enter Employee Name" class="form-control" value="{{ @$customer->employee_name ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Employee Code</label>
                            <input type="text" id="firstname" name="emp_code" parsley-trigger="change" placeholder="Enter Employee Code" class="form-control" value="{{ @$customer->emp_code ?? '' }}" {{ isset($is_edit) ? 'readonly' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Home Phone</label>
                            <input type="text" id="firstname" name="home_phone" parsley-trigger="change" placeholder="Enter Home Phone" class="form-control" value="{{ @$customer->home_phone ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">IMEI<span class="text-danger">*</span></label>
                            <input type="text" id="firstname" name="imei" parsley-trigger="change" placeholder="Enter IMEI" class="form-control" value="{{ @$customer->imei ?? '' }}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Phone Number<span class="text-danger">*</span></label>
                            <input type="text" id="firstname" name="sim_number" parsley-trigger="change" placeholder="Enter Phone Number" class="form-control" value="{{ @$customer->sim_number ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Category<span class="text-danger">*</span></label>
                            <input type="text" id="firstname" name="category" parsley-trigger="change" placeholder="Enter Category" class="form-control" value="{{ @$customer->category ?? '' }}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Username<span class="text-danger">*</span></label>
                            <input type="text" id="firstname" name="username" parsley-trigger="change" placeholder="Enter Username" class="form-control" value="{{ @$customer->username ?? '' }}" {{ isset($is_edit) ? 'readonly' : '' }} required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Password @if(!isset($customer))<span class="text-danger">*</span>@endif</label>
                            <input type="text" id="firstname" name="password" parsley-trigger="change" placeholder="Enter Password" class="form-control" {{(isset($customer)?'':'required')}}>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Reminder</label>
                            <input type="text" id="firstname" name="reminder" parsley-trigger="change" placeholder="Enter reminder" class="form-control" value="{{ @$customer->reminder ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Shop Close Pattern</label>
                            <textarea name="shop_close_pattern" parsley-trigger="change" placeholder="Enter Shop Close Pattern" class="form-control">{{ @$customer->shop_close_pattern ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">No Order Pattern</label>
                            <textarea name="no_order_pattern" parsley-trigger="change" placeholder="Enter No Order Pattern" class="form-control">{{ @$customer->no_order_pattern ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Order Recieved Pattern</label>
                            <textarea name="order_recieved_pattern" parsley-trigger="change" placeholder="Enter Order Recieved Pattern" class="form-control">{{ @$customer->order_recieved_pattern ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Address</label>
                            <textarea name="address" parsley-trigger="change" placeholder="Enter Address" class="form-control">{{ @$customer->address ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                   <div class="form-group mb-3 text-right">
                           <input type="hidden" value="{{ @$customer->hashid }}" name="customer_id" />
                           <input type="hidden" value="{{ hashids_encode(@$customer->u_id) }}" name="u_id" /> 
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