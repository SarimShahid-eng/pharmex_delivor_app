@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="">Item</a></li>
                    <li class="breadcrumb-item active">{{ (@$is_edit) ? 'Edit' : 'Add' }}</li>
                </ol>
            </div>
            <h4 class="page-title">{{ (@$is_edit) ? 'Edit' : 'Add' }} Item</h4>
        </div>
    </div>
</div>
@if(auth()->user()->is_admin)
<div class="row">
    <div class="col-lg-12">
        <div class="card custom_filters">
            <div class="card-header bg-dark text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#filters_div" role="button" aria-controls="filters_div" class=""><i class="mdi mdi-plus"></i></a>
                </div>
                <h4 class="card-title mb-0 text-white">Item <small>(Import CSV File)</small></h4>
            </div>
            <div id="filters_div" class="card-body collapse show ">
                <form action="{{ route('admin.item.import') }}" method="post" class="row align-items-end justify-content-center mb-2 ajaxForm" enctype='multipart/form-data'>
                     @csrf
                     <div class="col-12">
                        <div class="row">
                           <div class="col-sm-4 text-center offset-sm-3">
                               <label for="order_no" class="sr-onlys mr-1">Item CSV</label>
                               <input type="file" name="csv_file" accept=".csv,.CSV" id="csv" class="form-control-file" required="">
                               <a href="{{ asset('uploads/csvsheet/items.csv') }}" class="d-block mt-3">Item Format</a>
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
</div>
@endif
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">{{ (@$is_edit) ? 'Edit' : 'Add New' }} Item</h4>
           <!--  <p class="text-muted font-14 m-b-20">
                Here you can {{ (@$is_edit) ? 'update' : 'create' }} Cargo Service.
            </p> -->

            <form action="{{ route('admin.item.save') }}" class="ajaxForm" method="post" novalidate>
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name">Item Code<span class="text-danger">*</span></label>
                        <input type="text" name="item_code" parsley-trigger="change" required  class="form-control" id="item_code" value="{{ isset($item_data) ? $item_data->item_code : '' }}">

                    </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name">Category<span class="text-danger">*</span></label>
                        <select class="form-control" name="categories_id" required>
                            <option value="">Choose</option>
                            
                            @foreach($cat_data as $cat_data)
                                <option {{ isset($cat_data) && @$cat_data->id == @$item_data->categories_id ? 'selected' : ''}} value="{{ $cat_data->hashid }}">{{ $cat_data->category_name }}</option>
                           @endforeach
                          
                        </select>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label for="url">Item Name<span class="text-danger">*</span></label>
                        <input type="text" name="item_name" parsley-trigger="change" required  class="form-control" id="itme_name" value="{{ isset($item_data) ? $item_data->item_name : '' }}">
                    </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label for="url">Unit Name<span class="text-danger">*</span></label>
                        <input type="text" name="unit_name" parsley-trigger="change" required  class="form-control" id="unit_name" value="{{ isset($item_data) ? $item_data->unit_name : '' }}">
                    </div>
                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label for="url">Unit Size<span class="text-danger">*</span></label>
                        <input type="text" name="unit_size" parsley-trigger="change" required  class="form-control" id="unit_size" value="{{ isset($item_data) ? $item_data->unit_size : '' }}">
                    </div>
                    
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label for="url">Unit Price<span class="text-danger">*</span></label>
                        <input type="number" name="unit_cost" parsley-trigger="change" required class="form-control" id="unit_cost" value="{{ isset($item_data) ? $item_data->unit_cost : '' }}">
                    </div>
                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label for="url">CTN Size<span class="text-danger">*</span></label>
                        <input type="number" name="ctn_size" parsley-trigger="change" required  class="form-control" id="ctn_size" value="{{ isset($item_data) ? $item_data->ctn_size : '' }}">
                    </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label for="url">CTN Price<span class="text-danger">*</span></label>
                        <input type="number" name="ctn_cost" parsley-trigger="change" required class="form-control" id="ctn_cost" value="{{ isset($item_data) ? $item_data->ctn_cost : '' }}" readonly="">
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label for="url">Par Level<span class="text-danger">*</span></label>
                        <input type="number" name="par_levels" parsley-trigger="change" required class="form-control" id="url" value="{{ isset($item_data) ? $item_data->par_level : '' }}" >
                    </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group">
                        
                        <label for="name">Group<span class="text-danger">*</span></label>
                        <select class="form-control" name="group" required>
                            <option value="">Select Group</option>
                             
                            <option value="hot" {{ isset($item_data) && strtolower($item_data->groups) == 'hot'? 'selected=""' : '' }} >HOT</option>
                            <option value="cold" {{ isset($item_data) && strtolower($item_data->groups) == 'cold'? 'selected=""' : '' }}>COLD</option>
                           
                        </select>
                        <!-- <input type="text" name="item_code" parsley-trigger="change" required placeholder="Enter Item Code"  id="name" value="{{ isset($cargo_service) ? $cargo_service->name : '' }}"> -->
                    </div>
                    </div>
                </div>    
                <div class="row">
                    <div class="col-sm-6">
                    <div class="form-group mb-3">
                        <label for="url">Notes</label>
                        <textarea rows="3" name="description" parsley-trigger="change" class="form-control" id="desc">{{ isset($item_data) ? $item_data->description : '' }}</textarea>
                    </div>    
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group" id="rights">
                        <label for="url">Taxable</label>
                        <input type="checkbox" class="form-control" name="stock_in"  data-toggle="switchery" data-size="small" data-color="#1bb99a" value="yes" {{ isset($item_data) && $item_data->taxable == 'yes' ? 'checked=""' : '' }} />
                        <!-- <input type="checkbox" name="taxable" parsley-trigger="change" id="url" value="yes" {{ isset($item_data) && $item_data->taxable == 'yes' ? 'checked=""' : '' }}> -->
                         (Check If this item is Taxable)
                    </div>
                    </div>
                </div>    
                <div class="form-group mb-3 text-right">
                    <input type="hidden" value="{{ isset($item_data) ? $item_data->hashid : '' }}" name="item_id" />
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
<!-- <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script> -->
<script>
$(document).ready(function () {

    $(document).ready(function() {
        $('#unit_cost').keyup(function() { 
            var unit_cost = $('#unit_cost').val();
            var ctn_size = $('#ctn_size').val(); 
            ctn_cost = unit_cost*ctn_size; 
            $('#ctn_cost').val(ctn_cost);
        });
        $('#ctn_size').keyup(function() { 
            var unit_cost = $('#unit_cost').val();
            var ctn_size = $('#ctn_size').val(); 
            ctn_cost = unit_cost*ctn_size; 
            $('#ctn_cost').val(ctn_cost); 
        });
   })
});
</script>
@endsection
