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
    .table_main {
    display: block;
    overflow-x: scroll;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">employees</li>
                </ol>
            </div>
            <h4 class="page-title">All Employees</h4>
        </div>
    </div>
</div>
<!-- <div class="row">
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
</div> -->

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Employees</h4>
            @if(CommonHelpers::rights('employees_add'))    
                <a class="d-inline-block btn btn-primary waves-effect waves-light" href="{{ route('admin.employee.add') }}">Add New Employee</a>
            @endif    
            </div>
            <p class="sub-header">Following is the list of all the Employees.</p>
            <div class="table_main">
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="10">S.No</th>
                        <th>Employee Name</th>
                        <!-- <th>Reminder</th> -->
                        <th>Employee Code</th>
                        <th>Home Phone</th>
                        <th>IMEI</th>
                        <th>Phone Number</th>
                        <th>Category</th>
                        <th>Tasks/Target</th>
                        <!-- <th>Shop Close Pattern</th>
                        <th>No Order Pattern</th>
                        <th>Order Recieved Pattern</th>
                        <th>Address</th> -->
                        <th style="width: 135px;">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    
                                    <h4 class="modal-title" id="myLargeModalLabel">Details</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                    <div class="modal-body" >
                                        <div id="item_data"></div>
                                        <!-- <table class="table dt_table table-bordered w-100 nowrap responsive" id="">
                                           <thead>
                                               <tr>
                                                   <th>Username</th>
                                                   <th>Reminder</th>
                                                   <th>Shop Close Pattern</th>
                                                   <th>No Order Pattern</th>
                                                   <th>Order Recieved Pattern</th>
                                                   <th>Address</th>
                                               </tr>
                                           </thead> 
                                           <tbody ></tbody>
                                        </table>
                                        <div id="loader">
                                            <div class="spinner">Loading...</div>
                                        </div>
                                    </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
        </div>


        <div class="modal fade target-form" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myLargeModalLabel">Add Target</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                    <div class="modal-body" >
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group mb-3">
                                                    <label for="region_name">Month<span class="text-danger">*</span></label>
                                                    <select class="form-control" name="day" required="">
                                                        <option value="">select</option>
                                                    <?php for($y=0; $y<=11; $y++){ $a = date('m-Y', mktime(0, 0, 0, date('m')+$y, 1, date('Y'))); ?>
                                                           <option value="{{  date('d-m-Y', mktime(0, 0, 0, date('m')+$y, 1, date('Y'))) }}" {{ @$a == @$task_data->month ? 'selected' : ''}}>{{  date('F Y', mktime(0, 0, 0, date('m')+$y, 1, date('Y'))) }}</option>

                                                    <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
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
        ajax: "{{ route('admin.filter_employee.list') }}",
        columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'employee_name', name: 'employee_name'},
            {data: 'emp_code', name: 'emp_code'},
            // {data: 'reminder', name: 'reminder'},
            {data: 'home_phone', name: 'home_phone'},
            {data: 'imei', name: 'imei'},
            {data: 'sim_number', name: 'sim_number'},
            {data: 'category', name: 'category'},
            {data: 'view_task', name: 'view_task',orderable: false},
            {data: 'action',name: 'action',orderable: false}
        ]
    }); table.on('draw', function () {
        $('[data-toggle="switchery"]').each(function (a, e) {
            new Switchery($(this)[0], $(this).data())
        });
    });
});
$(document).on('click' , '.model_btn' , function(){
        id = $(this).data("id"); $("#item_data").html(''); $('#loader').show();
        $.ajax({
                url: "{{ route('admin.employee.model_data') }}",
                method: 'post',
                data: {'id':id, "_token": "{{ csrf_token() }}",},
                success: function (result) {
                    var items = JSON.parse(result); 
                        let _html = '<table class="table dt_table table-bordered w-100 nowrap responsive"><thead><thead><tr><th>Username</th><th>Reminder</th><th>Shop Close Pattern</th><th>No Order Pattern</th><th>Order Recieved Pattern</th><th>Address</th></tr></thead><tbody>';
                        $(items.items).each(function(index, items){ 
                _html += `<tr><td> ${items.username} </td><td> ${items.reminder} </td><td> ${items.shop_close_pattern} </td><td> ${items.no_order_pattern} </td><td> ${items.order_recieved_pattern} </td><td> ${items.address } </td></tr>`;
                }); _html+='</tbody>';
                        $('#loader').hide();
                        $("#item_data").html(_html);
                },
                error: function (msg) {

                },
                
        }); 
    });

$(document).on('click' , '.task_btn' , function(){
        id = $(this).data("id");   $("#item_data").html(''); $('#loader').show();
        $.ajax({
                url: "{{ route('admin.employee.task_data') }}",
                method: 'post',
                data: {'id':id, "_token": "{{ csrf_token() }}",},
                success: function (result) {
                    $('#loader').hide();
                    $("#item_data").html(result);
                },
                error: function (msg) {

                },
                
        }); 
});
$(document).on('click','.target_btn', function () {
    id = $(this).data("id");  
    $("#item_data").html(''); $('#loader').show();
        $.ajax({
                url: "{{ route('admin.employee.taget_data') }}",
                method: 'post',
                data: {'id':id, "_token": "{{ csrf_token() }}",},
                success: function (result) {
                    $('#loader').hide();
                    $("#item_data").html(result);
                },
                error: function (msg) {

                },
                
        }); 
});
$(document).on('submit','#add_target',function(e) {
    e.preventDefault();
    employee_id = $('#employee_id').val();
    month = $('#month').val();
    target = $('#target').val();
    task_id = $('#task_id').val();
    $("#item_data").html(''); $('#loader').show();
    $.ajax({
                url: "{{ route('admin.target.save') }}",
                method: 'post',
                data: {'employee_id':employee_id,'month':month,'target':target,'task_id':task_id, "_token": "{{ csrf_token() }}",},
                success: function (result) {
                   $('#loader').hide();
                   $("#item_data").html(result);
                },
                error: function (msg) {

                },
                
        }); 
});
function editTarget(emp_id,id,month,target) {
    month = '01-'+month; 
    $("#month option[value='"+month+"']").attr('selected', 'selected');
    $("#target").val(target);
    $("#task_id").val(id);
    $("#employee_id").val(emp_id);
}
$(document).on('click','.edit_target', function() {

    // $.ajax({
    //             url: "{{ route('admin.target.edit') }}",
    //             method: 'post',
    //             data: {'employee_id':employee_id,'month':month,'target':target,'task_id':task_id, "_token": "{{ csrf_token() }}",},
    //             success: function (result) {
    //                $('#loader').hide();
    //                $("#item_data").html(result);
    //             },
    //             error: function (msg) {

    //             },
                
    //     }); 
})

</script>
@endsection

