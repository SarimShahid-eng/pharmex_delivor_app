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
                    <li class="breadcrumb-item active">customers</li>
                </ol>
            </div>
            <h4 class="page-title">All Customers</h4>
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
                <h4 class="card-title mb-0 text-white">Filter & Customers Import</h4>
            </div>
            <div id="filters_div" class="card-body collapse" style="">
               <div class="row">
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0">Region Filter</h4>
                            <form method="post" class="row align-items-end justify-content-center mb-2 ajaxForm" enctype='multipart/form-data' id="filter_search">
                                @csrf

                               <div class="col-lg-12"> <br>
                                <div class="form-group mb-3 ">
                                        <label for="order_no" class="sr-onlys mr-1">Regions</label>
                                        <select id="region_id" name="region_id" parsley-trigger="change" class="form-control" required>
                                <option value="">Select Regions</option>
                                
                                @foreach($region_data as $region_data)
                                <option {{ isset($region_data) && @$region_data->id == @$customer->region_id ? 'selected' : ''}} value="{{ $region_data->hashid }}">{{ $region_data->region_name }}</option>
                                @endforeach
                            </select>
                                       
                                </div>
                                    <div class="form-group mb-3 text-right">

                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>

                                    </div>
                             </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0"> Customers Import</h4>
                            <p class="text-muted font-14 m-b-20">
                                Customers (Import CSV File)
                            </p>
                            <form action="{{ route('admin.customers.import') }}" method="post" class="row align-items-end justify-content-center mb-2 ajaxForm" enctype='multipart/form-data'>
                                @csrf

                               <div class="col-lg-12">
                                <div class="form-group mb-3 ">
                                        <label for="order_no" class="sr-onlys mr-1">Import CSV</label>
                                        <input type="file" name="csv_file" accept=".csv,.CSV" id="csv" class="form-control" required="">
                                       
                                </div>
                                    <div class="form-group mb-3 text-right">

                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Import</button>

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
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Customers</h4>
                <a class="d-inline-block btn btn-primary waves-effect waves-light" href="{{ route('admin.customers.add') }}">Add New Customers</a>
            </div>
            <p class="sub-header">Following is the list of all the customers.</p>
            <div class="table-responsive">
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="20">S.No</th>
                        <th>Customer Name</th>
                        <th>ORG Name</th>
                        <th>Customer Mobile</th>
                        <th>Regions</th>
                        <th>Item Reminder</th>
                        <th>Location</th>
                        <th>Value</th>
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
$(document).ready(function () {

    $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 0,
        lengthMenu: [10, 20, 50, 100, 200, 500],
        ajax: "{{ route('admin.customers.list') }}",
        columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'customer_name', name: 'customer_name'},
            {data: 'org_name', name: 'org_name'},
            {data: 'ph_num', name: 'ph_num'},
            {data: 'regions.region_name', name: 'regions.region_name'},
            {data: 'item_reminder', name: 'item_reminder'},
            {data: 'location', name: 'location'},
            {data: 'value',name: 'value'},
            {data: 'action',name: 'action',orderable: false}
        ]
    });
});

$('#filter_search').on('submit',function(e) {
    e.preventDefault();
    var table = $('#laravel_datatable').DataTable();
    var region_id = $('#region_id').val(); 
    if ($.fn.DataTable.isDataTable( '#laravel_datatable' ) ) { 
          table.destroy(); 
        $('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 0,
            lengthMenu: [10, 20, 50, 100, 200, 500],
            ajax: {
                url:"{{ route('admin.filter_customers.list') }}",
                type: "post",
                data: {
                      'region_id':region_id,
                      "_token": "{{ csrf_token() }}",
                },
            },
            columns: [
                {data: 'rownum', name: 'rownum'},
                {data: 'customer_name', name: 'customer_name'},
                {data: 'org_name', name: 'org_name'},
                {data: 'ph_num', name: 'ph_num'},
                {data: 'regions.region_name', name: 'regions.region_name'},
                {data: 'item_reminder', name: 'item_reminder'},
                {data: 'location', name: 'location'},
                {data: 'value',name: 'value'},
                {data: 'action',name: 'action',orderable: false}
            ]
        });  
    }
})
</script>
@endsection

