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

<style>
    .dt_table tr td{max-width: 120px;white-space: break-spaces !important;}
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Customers</h4>
                @if(CommonHelpers::rights('customer_add'))
                <a class="d-inline-block btn btn-primary waves-effect waves-light" href="{{ route('admin.customers.add') }}">Add New Customers</a>
                @endif
            </div>
            <p class="sub-header">Following is the list of all the customers.</p>
            <div class="table-responsive">
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable" style="font-size:13px">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Customer Code</th>
                        <th>Customer Name</th>
                        <th>Contact Person</th>
                        <th>Ph#</th>
                        <th>Rout</th>
                        <th>Town</th>
                        <th>Address</th>
                        <th>Lic#</th>
                        <th>Lic Exp.</th>
                        <th>Status</th>
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
<link href="{{ asset('admin_assets') }}/libs/switchery/switchery.min.css" rel="stylesheet" type="text/css" />
<script src="{{ asset('admin_assets') }}/libs/switchery/switchery.min.js"></script>
<script>
    $('[data-toggle="switchery"]').each(function(a, e) {
        new Switchery($(this)[0], $(this).data())
    });
</script>
<script>
$(document).ready(function () {

   var table = $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 0,
        lengthMenu: [10, 20, 50, 100, 200, 500],
        ajax: "{{ route('admin.customers.list') }}",
        columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'customer_code', name: 'customer_code'},
            {data: 'customer_name', name: 'customer_name'},
            {data: 'contact_person', name: 'contact_person'},
            {data: 'ph_num', name: 'ph_num'},
            {data: 'regions.region_name', name: 'regions.region_name'},
            {data: 'town.town', name: 'town.town'},
            {data: 'address', name: 'address'},
            {data: 'license_no', name: 'license_no'},
            {data: 'lic_exp', name: 'lic_exp'},
            {data: 'status',name: 'status'},
            {data: 'action',name: 'action',orderable: false}
        ]
    }); table.on('draw', function () {
        $('[data-toggle="switchery"]').each(function (a, e) {
            new Switchery($(this)[0], $(this).data())
        });
    });
});

// $('#filter_search').on('submit',function(e) {
//     e.preventDefault();
//     var table = $('#laravel_datatable').DataTable();
//     var region_id = $('#region_id').val(); 
//     if ($.fn.DataTable.isDataTable( '#laravel_datatable' ) ) { 
//           table.destroy(); 
//         $('#laravel_datatable').DataTable({
//             processing: true,
//             serverSide: true,
//             pageLength: 0,
//             lengthMenu: [10, 20, 50, 100, 200, 500],
//             ajax: {
//                 url:"{{ route('admin.filter_customers.list') }}",
//                 type: "post",
//                 data: {
//                       'region_id':region_id,
//                       "_token": "{{ csrf_token() }}",
//                 },
//             },
//             columns: [
//                 {data: 'rownum', name: 'rownum'},
//                 {data: 'customer_code', name: 'customer_code'},
//                 {data: 'customer_name', name: 'customer_name'},
//                 {data: 'org_name', name: 'org_name'},
//                 {data: 'ph_num', name: 'ph_num'},
//                 {data: 'regions.region_name', name: 'regions.region_name'},
//                 {data: 'town.town', name: 'town.town'},
//                 {data: 'item_reminder', name: 'item_reminder'},
//                 {data: 'location', name: 'location'},
//                 {data: 'value',name: 'value'},
//                 {data: 'action',name: 'action',orderable: false}
//             ]
//         });  
//     }
// })
</script>
@endsection

