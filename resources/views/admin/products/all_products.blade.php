@extends('layouts.admin')
@section('content')

<style>
    .pagination-rounded .page-link{
        border-radius: 0 !important;
        border-color: #fff !important;
    }
    .pagination.pagination-rounded{
        margin-top: 8px !important;
    }

    .page-item.active .page-link{
        color: #333 !important;
        border: 1px solid #979797;
        background-color: white;
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fff), color-stop(100%, #dcdcdc)) !important;
        background: -webkit-linear-gradient(top, #fff 0%, #dcdcdc 100%) !important;
        background: -moz-linear-gradient(top, #fff 0%, #dcdcdc 100%) !important;
        background: -ms-linear-gradient(top, #fff 0%, #dcdcdc 100%) !important;
        background: -o-linear-gradient(top, #fff 0%, #dcdcdc 100%) !important;
        background: linear-gradient(to bottom, #fff 0%, #dcdcdc 100%) !important;
        border: 1px solid #dcdcdc!important;
    }
    .paginate_button.page-item a{
        padding: 0.5em 1em !important;
        border: 1px solid transparent;
    }
    .paginate_button.page-item a:hover{
    outline: none;
    background-color: #2b2b2b;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #2b2b2b), color-stop(100%, #0c0c0c));
    background: -webkit-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);
    background: -moz-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);
    background: -ms-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);
    background: -o-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);
    background: linear-gradient(to bottom, #2b2b2b 0%, #0c0c0c 100%);
    box-shadow: inset 0 0 3px #111;
    color: #fff;
    border: 1px solid #111;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">products</li>
                </ol>
            </div>
            <h4 class="page-title">All Products</h4>
        </div>
    </div>
</div>
@if(isset($product))
@dd('sss')
@endif
<div class="row">
    <div class="col-lg-12">
        <div class="card-box" style="height: 232px;">
            <h4 class="header-title m-t-0">All Companies </h4>
            <p class="text-muted font-14 m-b-20">

            <form id="filter_search">
                @php
                    $hashId=request()->get('hashedId');
                @endphp
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="region_name">Company Name<span class="text-danger">*</span></label>
                            <select class="form-control" required="" id="company_id">
                                <option value="">Select...</option>
                                @foreach($company_data as $company_data)
                                <option
                                @if($hashId == $company_data->hashid) selected @endif
                                value="{{ $company_data->hashid }}">{{ $company_data->company_name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>    
                <div class="form-group mb-3 text-right">
                    <input type="hidden" value="{{ @$region->hashid }}" name="category_id" />
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        Submit
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Products</h4>
                @if(CommonHelpers::rights('product_add')) 
                <a class="d-inline-block btn btn-primary waves-effect waves-light" href="{{ route('admin.products.add') }}">Add New Product</a>
                @endif
            </div>
            <p class="sub-header">Following is the list of all the product.</p>
            <div class="table-responsive">
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="20">S.No</th>
                        <th>Company <br> Name</th>
                        <th>Company <br> Group</th>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Packing</th>
                        <th>Enlist.Code</th>
                        <th>Unit Price</th>
                        <th>Retail Price</th>
                        <th>Gst %</th>
                        <th>Gst Amt</th>
                        <!-- <th>Image</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js
"></script>

<script>
// $(document).ready(function () {

//     $('#laravel_datatable').DataTable({
//         processing: true,
//         serverSide: true,
//         pageLength: 0,
//         lengthMenu: [10, 20, 50, 100, 200, 500],
//         ajax: "{{ route('admin.products.list') }}",
//         columns: [
//             {data: 'rownum', name: 'rownum'},
//             {data: 'companies.company_name', name: 'companies.company_name'},
//             {data: 'product_code', name: 'product_code'},
//             {data: 'product_name', name: 'product_name'},
//             {data: 'packing', name: 'packing'},
//             {data: 'enlist_code', name: 'enlist_code'},
//             {data: 'unit_price', name: 'unit_price'},
//             {data: 'retail_price', name: 'retail_price'},
//             {data: 'gst', name: 'gst'},
//             {data: 'gst_amt', name: 'gst_amt'},
//             {data: 'img', name: 'img'},
//             {data: 'action',name: 'action',orderable: false}
//         ]
//     });
// });
@if($hashId)
    $(document).ready(function() {
        // Set the selected company_id based on hashId
        $('#company_id').val('{{ $hashId }}'); // Set the select input value
        submitForm(); // Trigger form submission via AJAX
    });
@endif

$('#filter_search').on('submit', function(e) {
    e.preventDefault();  // Prevent default form submission (no page reload)
    submitForm();        // Call AJAX form submission
});

function submitForm() {
    var table = $('#laravel_datatable').DataTable();
    var company_id = $('#company_id').val(); 

    // Reinitialize DataTable with updated data
    if ($.fn.DataTable.isDataTable('#laravel_datatable')) {
        table.destroy();  // Destroy the old DataTable instance
    }

    $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 0,
        lengthMenu: [10, 20, 50, 100, 200, 500],
        ajax: {
            url: "{{ route('admin.products.list') }}",
            type: "POST",
            data: {
                'company_id': company_id,
                "_token": "{{ csrf_token() }}",
            },
        },
        columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'companies.company_name', name: 'companies.company_name'},
            {data: 'company_group.company_name', name: 'company_group.company_name'},
            {data: 'product_code', name: 'product_code'},
            {data: 'product_name', name: 'product_name'},
            {data: 'packing', name: 'packing'},
            {data: 'enlist_code', name: 'enlist_code'},
            {data: 'unit_price', name: 'unit_price'},
            {data: 'retail_price', name: 'retail_price'},
            {data: 'gst', name: 'gst'},
            {data: 'gst_amt', name: 'gst_amt'},
            {data: 'action', name: 'action', orderable: false}
        ]
    });
}

</script>
@endsection

