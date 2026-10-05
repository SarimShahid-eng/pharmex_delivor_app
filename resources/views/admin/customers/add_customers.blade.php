@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Customer</a></li>
                    <li class="breadcrumb-item active">{{@$is_edit ? 'Edit' : 'Add New'}} Customer</li>
                </ol>
            </div>
            <h4 class="page-title">{{@$is_edit ? 'Edit' : 'New'}} Customer</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom_filters">
            <div class="card-header bg-dark text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#filters_div" role="button" aria-controls="filters_div" class="collapsed" aria-expanded="false"><i class="mdi mdi-minus"></i></a>
                </div>
                <h4 class="card-title mb-0 text-white">Customers Import</h4>
            </div>
            <div id="filters_div" class="card-body collapse" style="">
               <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <h4 class="header-title m-t-0"> Customers Import</h4>
                            <p class="text-muted font-14 m-b-20">
                                Customers (Import CSV File)
                            </p>
                            <form action="{{ route('admin.customers.import') }}" method="post" class="row align-items-end justify-content-center mb-2 ajaxForm" enctype='multipart/form-data'>
                                @csrf
                              
                               <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group mb-3 ">
                                            <label for="order_no" class="sr-onlys mr-1">Rout</label>
                                            <select onchange="adminRoleRights(this.value)" id="rout_file" name="region_id" parsley-trigger="change" class="form-control" required>
                                                <option value="">Select...</option>
                                                @foreach($region_data as $val)
                                                <option value="{{ $val->hashid }}">{{ $val->region_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group mb-3 ">
                                            <label for="order_no" class="sr-onlys mr-1">Town</label>
                                            <select id="town_file" name="town_id" parsley-trigger="change" class="form-control" required>
                                                <option value="">Select...</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group mb-3 ">
                                            <label for="order_no" class="sr-onlys mr-1">Import CSV</label>
                                            <input type="file" name="csv_file" accept=".csv,.CSV" id="csv" class="form-control" required="">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                    <div class="form-group mb-3 text-right">

                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Import</button>
                                    </div>  
                                    </div>
                                 <a href="{{ asset('uploads/csvsheet/customers.csv') }}" class="d-block ">Customers Format</a>

                                 <span><b>Note</b>: Use only for additional new entries, or use edit to change the names of existing names</span>
                             </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">{{@$is_edit ? 'Edit' : 'Add New'}} Customer</h4>
            <form action="{{ route('admin.customer.save') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Customer Code<span class="text-danger">*</span></label>
                            <input type="text" id="firstname" name="customer_code" parsley-trigger="change" placeholder="Enter Customer Code" class="form-control" value="{{ @$customer->customer_code ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Customer Name<span class="text-danger">*</span></label>
                            <input type="text" id="firstname" name="customer_name" parsley-trigger="change" placeholder="Enter Customer Name" class="form-control" value="{{ @$customer->customer_name ?? '' }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Contact Person</label>
                            <input type="text" id="firstname" name="contact_person" parsley-trigger="change" placeholder="Enter Contact Person" class="form-control" value="{{ @$customer->contact_person ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Customer Mobile</label>
                            <input type="text" id="firstname" name="ph_num" parsley-trigger="change" placeholder="Enter Customer Mobile" class="form-control" value="{{ @$customer->ph_num ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="rout">Rout<span class="text-danger">*</span></label>
                            <select onchange="adminRoleRights(this.value)" id="rout" name="region_id" parsley-trigger="change" class="form-control" required>
                                <option value="">Select Rout</option>
                                
                                @foreach($region_data as $region_data)
                                <option {{ isset($region_data) && @$region_data->id == @$customer->region_id ? 'selected' : ''}} value="{{ $region_data->hashid }}">{{ $region_data->region_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="town">Town </label>
                            <select onchange="adminRoleRights(this.value)" id="town" name="town_id" parsley-trigger="change" class="form-control">
                                <option value="">Select...</option>
                                @if(!empty(@$town_data)))
                                @foreach($town_data as $town_data)
                                <option {{ isset($town_data) && @$town_data->id == @$customer->town_id ? 'selected' : ''}} value="{{ $town_data->hashid }}">{{ $town_data->town }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Address</label>
                            <input type="text" id="firstname" name="location" parsley-trigger="change" placeholder="Enter Address" class="form-control" value="{{ @$customer->address ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">License No.</label>
                            <input type="text" id="license_no" name="license_no" parsley-trigger="change" placeholder="Enter License No" class="form-control" value="{{ @$customer->license_no }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">License Exp.</label>
                            <input type="date" id="firstname" name="license_exp" parsley-trigger="change" placeholder="Enter License No" class="form-control" value="{{ @$customer->license_exp }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Value</label>
                            <input type="text" id="firstname" name="value" parsley-trigger="change" placeholder="Enter Value" class="form-control" value="{{ @$customer->value ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Username <span class="text-danger">*</span></label>
                            <input type="text" id="username" name="username" parsley-trigger="change" placeholder="Enter Username" class="form-control" value="{{ @$customer->username }}" {{ isset($is_edit) ? 'readonly' : '' }} required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Password <span class="text-danger">*</span></label>
                            <input type="password" id="password" name="password" parsley-trigger="change" placeholder="Enter password" class="form-control" value="" required>
                        </div>
                    </div>
                </div>

                   <div class="form-group mb-3 text-right">
                           <input type="hidden" value="{{ @$customer->hashid }}" name="customer_id" />
                           <input type="hidden" value="{{ hashids_encode(@$customer->u_id) }}" name="user_id" />
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
    <script type="text/javascript">
        $('#rout').change(function() {
            var rout_id = $('#rout').val();
            $.ajax({
            type: "POST",
            url: "{{ route('admin.get_town') }}",
            data: {'rout_id': rout_id,"_token": "{{ csrf_token() }}",},
           // dataType: 'JSON',
            success: function (result) {  
                $("#town").html(result);
            },
            error: function (msg) {

            },
        }); 
        })
    </script>
    <script type="text/javascript">
        $('#rout_file').change(function() {
            var rout_id = $('#rout_file').val();
            $.ajax({
            type: "POST",
            url: "{{ route('admin.get_town') }}",
            data: {'rout_id': rout_id,"_token": "{{ csrf_token() }}",},
           // dataType: 'JSON',
            success: function (result) {  
                $("#town_file").html(result);
            },
            error: function (msg) {

            },
        }); 
        })
    </script>
@endsection