@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Product</a></li>
                    <li class="breadcrumb-item active">{{@$is_edit ? 'Edit' : 'Add New'}} Product</li>
                </ol>
            </div>
            <h4 class="page-title">{{@$is_edit ? 'Edit' : 'New'}} Product</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom_filters">
            <div class="card-header bg-dark text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#filters_div" role="button" aria-controls="filters_div" class=""><i class="mdi mdi-plus"></i></a>
                </div>
                <h4 class="card-title mb-0 text-white">Product <small>(Import CSV File)</small></h4>
            </div>
            <div id="filters_div" class="card-body collapse show ">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('admin.products.import') }}" method="post" class="row align-items-end justify-content-center mb-2 ajaxForm" enctype='multipart/form-data'>
                            @csrf
                            <div class="col-12">
                               <div class="row">
                                  <div class="col-sm-4 text-center offset-sm-3">
                                      <label for="order_no" class="sr-onlys mr-1">Product CSV</label>
                                      <input type="file" name="csv_file" accept=".csv,.CSV" id="csv" class="form-control-file" required="">
                                      <a href="{{ asset('uploads/csvsheet/products.csv') }}" class="d-block mt-3">Product Format</a>
                                       <span><b>Note</b>: Use only for additional new entries, or use edit to change the names of existing names</span>
       
                                  </div>
       
                                   <div class="col-sm-2 ">
                                       <button type="submit" class="btn btn-primary waves-effect waves-light" style="margin-top: 24px;">Import</button>
                                  </div>
                               </div>
                            </div>
                       </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.products.price.import') }}" method="post" class="row align-items-end justify-content-center mb-2 ajaxForm" enctype='multipart/form-data'>
                            @csrf
                            <div class="col-12">
                               <div class="row">
                                  <div class="col-sm-4 text-center offset-sm-3">
                                      <label for="order_no" class="sr-onlys mr-1">Product Price Update CSV</label>
                                      <input type="file" name="csv_file" accept=".csv,.CSV" id="csv" class="form-control-file" required="">
                                      <a href="{{ asset('uploads/csvsheet/product_price_update.csv') }}" class="d-block mt-3">Product Price Update Format</a>
                                       <span><b>Note</b>: Use only for update product price which already exists</span>
       
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
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">{{@$is_edit ? 'Edit' : 'Add New'}} Product</h4> <br>
            <form action="{{ route('admin.products.save') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="region_name">Company<span class="text-danger">*</span></label>
                            <select class="form-control" name="group_id" id="group_id" required="">
                                <option value="">select</option>
                                @foreach($group_data as $group_data)
                                <option {{ isset($group_data) && @$group_data->id == @$customer->company_id ? 'selected' : ''}} value="{{ $group_data->hashid }}">{{ $group_data->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                     <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="region_name">Group</label>
                            <select class="form-control" name="sub_category" id="sub_category">
                                <option value="">select</option>
                                @if(!empty(@$sub_company)))
                                @foreach($sub_company as $sub_company)
                                <option {{ isset($sub_company) && @$sub_company->id == @$customer->sub_company_id ? 'selected' : ''}} value="{{ $sub_company->hashid }}">{{ $sub_company->company_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="product_code">Product Code<span class="text-danger">*</span></label>
                            <input type="text" id="product_code" name="product_code" parsley-trigger="change" placeholder="Enter Product Code" class="form-control" value="{{ @$customer->product_code ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="product_name">Product Name<span class="text-danger">*</span></label>
                            <input type="text" id="product_name" name="product_name" parsley-trigger="change" placeholder="Enter Product Name" class="form-control" value="{{ @$customer->product_name ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="packing">Packing<span class="text-danger">*</span></label>
                            <input type="text" id="packing" name="packing" parsley-trigger="change" placeholder="Enter Product Packing" class="form-control" value="{{ @$customer->packing ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="enlist_code">Enlist.Code</label>
                            <input type="text" id="enlist_code" name="enlist_code" parsley-trigger="change" placeholder="Enter Enlist.Cod" class="form-control" value="{{ @$customer->enlist_code ?? '' }}">
                        </div>
                    </div> 
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Unit Price<span class="text-danger">*</span></label>
                            <input type="text" id="firstname" name="unit_price" parsley-trigger="change" placeholder="Enter Unit Price" class="form-control" value="{{ @$customer->unit_price ?? '' }}" required="">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="retail">Retail<span class="text-danger">*</span></label>
                            <input type="text" id="retail" name="retail" parsley-trigger="change" placeholder="Enter Retail Price" class="form-control" value="{{ @$customer->retail_price ?? '' }}" required="">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="gst">Gst %</label>
                            <input type="text" id="gst" name="gst" parsley-trigger="change" placeholder="Enter Gst %" class="form-control" value="{{ @$customer->gst ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="gst_amt">Gst Amt</label>
                            <input type="text" id="gst_amt" name="gst_amt" parsley-trigger="change" placeholder="Enter Gst Amt" class="form-control" value="{{ @$customer->gst_amt ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Image</label>
                            <input type="file" id="firstname" name="img" parsley-trigger="change" placeholder="Enter Unit Price" class="form-control" value="">
                        </div>
                    </div>
                </div>

                   <div class="form-group mb-3 text-right">
                           <input type="hidden" value="{{ @$customer->hashid }}" name="product_id" />
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
        $('#group_id').change(function() {
            var company_id = $('#group_id').val();
            $.ajax({
            type: "POST",
            url: "{{ route('admin.get_sub_company') }}",
            data: {'company_id': company_id,"_token": "{{ csrf_token() }}",},
           // dataType: 'JSON',
            success: function (result) {  
                $("#sub_category").html(result);
            },
            error: function (msg) {

            },
        }); 
        })
    </script>
@endsection
       