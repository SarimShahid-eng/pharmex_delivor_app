@extends('layouts.admin')
@section('content')
<style>
    #datatables_buttons_info h2{
        color: black !important;
    }
    #datatables_buttons_info{
        color: black !important;
    }
    table.dataTable.nowrap td, table.dataTable.nowrap th{
        white-space: normal !important;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">File</li>
                </ol>
            </div>
            <h4 class="page-title">{{ $title }}</h4>
        </div>
    </div>
</div>



@if(auth()->user()->is_admin || CommonHelpers::rights('routs_add'))
<div class="row row-eq-height">
    <div class="col-lg-6">
        <div class="card-box" style="height: auto;">
            <h4 class="header-title m-t-0">Export File</h4>
            

            <!-- <form action="{{ route('admin.files.export') }}" method="post" enctype='multipart/form-data' novalidate>
                @csrf  -->
            <form id="filter_search">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="region_name">Date To<span class="text-danger">*</span></label>
                            <input type="date" name="dateTo" parsley-trigger="change" required class="form-control" id="dateTo">

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="region_name">Date Form<span class="text-danger">*</span></label>
                            <input type="date" name="dateForm" parsley-trigger="change" required class="form-control" id="dateForm">

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="area_name">Rout</label>
                            <select name="route" id="route" class="form-control">
                                <option value="">select...</option>
                                @foreach($route_data as $route_data_val)
                                    <option value="{{$route_data_val->hashid}}">{{$route_data_val->region_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="area_name">Town</label>
                            <select name="town" id="town" class="form-control">
                                <option value="">select...</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="region_name">Select User</label>
                            <select  name="user_id[]" id="user_id" class="form-control select2-multiple" data-toggle="select2" multiple="multiple">
                                <option value="">Select...</option>
                                <option value="0">Customer</option>
                                @foreach($emp_data as $emp_data)
                                <option value="{{$emp_data->id}}">{{$emp_data->employee_name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="area_name">Status</label>
                            <select name="order_status" id="order_status" class="form-control">
                                <option value="all">All</option>
                                <option value="booked">Booked</option>
                                <option value="processed">Processed</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-3 pt-1 text-right ml-auto">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                        <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                            Cancel
                        </button>
                    </div>

                </div>    





                
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-box" style="height: 94%;">
            <h4 class="header-title m-t-0">File Import</h4>
            

            <form action="{{ route('admin.files.import') }}" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="file_name">CSV File<span class="text-danger">*</span></label>
                            <input type="file" name="file_name" parsley-trigger="change" required class="form-control" id="branch_name" >

                        </div>
                    </div>
                    <!-- <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="area_name">Area Name</label>
                            <input type="text" name="area_name" parsley-trigger="change" placeholder="Enter Area Name" class="form-control" id="branch_name" value="{{ isset($region) ? $region->area_name : '' }}">

                        </div>
                    </div> -->
                </div>    
                @if(Session::get('msg'))
                <div class="row">
                    <div class="col-sm-5">
                        <h5 class="text-danger">{{ Session::get('msg') }}</h5>
                    </div>
                </div>
                @endif 
                
                @error('file_name')
                <div class="row">
                    <div class="col-sm-5">
                        <h5 class="text-danger">{{ $message }}</h5>
                    </div>
                </div>
                @enderror
                
                <div class="form-group mb-3 text-right">
                    <input type="hidden" value="{{ @$region->hashid }}" name="category_id" />
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
@endif
@if(Session::has('success'))
<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-success">
            {{Session::get('success')['msg']}} 
        </div>
    </div>
</div>
@if(count(Session::get('success')['customers'])>0)
<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-danger">
            These customers ids could not found
            {{Session::get('success')['customers']}} 
        </div>
    </div>
</div>
@endif
@if(count(Session::get('success')['products'])>0)
<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-danger">
            These products ids could not found
           {{Session::get('success')['products']}} 
        </div>
    </div>
</div>
@endif
@endif
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <input type="checkbox" name="" id="change_status" value="true"> Change all booked order status
            <div class="d-flex justify-content-end">
                <form action="{{ route('admin.files.file_export_csv') }}" method="post" enctype='multipart/form-data' novalidate id="export_csv">
                @csrf  
                <input type="hidden" name="dateTo" class="dateTo">
                <input type="hidden" name="dateForm" class="dateForm">
                <input type="hidden" name="user_id" class="user_id">
                <input type="hidden" name="status" class="status">
                <input type="hidden" name="route" class="route">
                <input type="hidden" name="town" class="town">
                <input type="hidden" name="change_status" class="is_status" value="0">
                <input type="hidden" name="csv_name" id="csv_name">
                <div class="order_id_inputs">
                    
                </div>
                <button type="submit" class="d-inline-block btn btn-primary waves-effect waves-light mr-2" href="">Export CSV</button>
                </form>
                <form action="{{ route('admin.files.file_export_azm') }}" method="post" enctype='multipart/form-data' novalidate >
                @csrf  
                <input type="hidden" name="dateTo" class="dateTo">
                <input type="hidden" name="dateForm" class="dateForm">
                <input type="hidden" name="user_id" class="user_id">
                <input type="hidden" name="status" class="status">
                <input type="hidden" name="route" class="route">
                <input type="hidden" name="town" class="town">
                <input type="hidden" name="change_status" class="is_status" value="0">
                <input type="hidden" name="csv_name" id="csv_name">
                <div class="order_id_inputs">
                    
                </div>
                <button type="submit" class="d-inline-block btn btn-primary waves-effect waves-light mr-2" href="">Export AZM</button>
                </form>
               <!--  <form action="{{ route('admin.files.export2') }}" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <input type="hidden" name="dateTo" class="dateTo">
                <input type="hidden" name="dateForm" class="dateForm">
                <input type="hidden" name="user_id" class="user_id">
                <input type="hidden" name="status" class="status">
                <input type="hidden" name="route" class="route">
                <input type="hidden" name="town" class="town">
                <div class="order_id_inputs">
                    
                </div>
                <button type="submit" class="d-inline-block btn btn-primary waves-effect waves-light mr-2" href="">Export EXCEL</button>
                </form> -->
                <form action="{{ route('admin.files.file_export_txt') }}" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <input type="hidden" name="dateTo" class="dateTo">
                <input type="hidden" name="dateForm" class="dateForm">
                <input type="hidden" name="user_id" class="user_id">
                <input type="hidden" name="status" class="status">
                <input type="hidden" name="route" class="route">
                <input type="hidden" name="town" class="town">
                <input type="hidden" name="change_status" class="is_status" value="0">
                <div class="order_id_inputs">
                    
                </div>
                <button type="submit" class="d-inline-block btn btn-primary waves-effect waves-light" href="">Export TXT</button>
                </form>

                <!-- <a class="d-inline-block btn btn-primary waves-effect waves-light" href="{{ route('admin.employee.add') }}">Export EXCEL</a> -->  
            </div> <br>
            <div class="table_main">
                    <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="max-width: 150px; width: 150px">Customer Name</th>
                                <th>Employee <br>Name</th>
                                <th>Rout</th>
                                <th>Town</th>
                                <th>Order Price</th>
                                <th>Order Date</th>
                                <th>Status</th>
                                <th></th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
            </div>
        
</div>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myLargeModalLabel">Details</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                    <div class="modal-body" >
                                        <div ></div>
                                        <table class="table dt_table table-bordered w-100 nowrap responsive" id="">
                                           <thead>
                                               <tr>
                                                   <th>Item Name</th>
                                                   <th>Price</th>
                                                   <th>QTY</th>
                                                   <th>Subtotal</th>
                                                   <th>Batch Code</th>
                                                   <th>Expriy Date</th>
                                               </tr>
                                           </thead> 
                                           <tbody id="item_data"></tbody>
                                        </table>
                                        <div id="loader">
                                            <div class="spinner">Loading...</div>
                                        </div>
                                    </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
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
<script type="text/javascript">
    $('#route').change(function() {
        var rout_id = $('#route').val();
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
<script>
$(document).ready(function () {
 $('#filter_search').on('submit',function(e) {
    e.preventDefault(); 
    var table = $('#laravel_datatable').DataTable();
    var user_id = $('#user_id').val(); 
    var dateTo = $('#dateTo').val();
    var dateForm = $('#dateForm').val();
    var order_status = $('#order_status').val();
    var route = $('#route').val();
    var town = $('#town').val();
    $('.user_id').val(user_id); 
    $('.dateTo').val(dateTo);
    $('.dateForm').val(dateForm);
    $('.status').val(order_status);
    $('.route').val(route);
    $('.town').val(town);
    if ($.fn.DataTable.isDataTable( '#laravel_datatable' )) { 
          table.destroy();
        $('#laravel_datatable').DataTable({
        "columnDefs": [{
                "defaultContent": "-",
                "targets": "_all"
            }],
        processing: true,
        serverSide: true,
        pageLength: 0,
        lengthMenu: [1000],
        ajax: {
            url: "{{ route('admin.files.data') }}",
            type: "post",
            data: {
                'user_id':user_id,
                'dateTo' :dateTo,
                'dateForm' :dateForm,
                'order_status' : order_status,
                'route' : route,
                'town' : town,
                "_token": "{{ csrf_token() }}",
            },
        },
  
        columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'customer_name', name: 'customer_name',orderable: false},
            {data: 'employee_name',name: 'employee_name'},
            {data: 'route_name',name: 'route_name'},
            {data: 'town_name',name: 'town_name'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'order_date', name: 'order_date'},
            {data: 'order_status', name: 'order_status'},
            {data: 'checkbox', name: 'checkbox'},
            {data: 'action', name: 'action',orderable: false},
        ],

    });  
    }      
});

});
</script>
<script type="text/javascript">
    $(document).on('click' , '.model_btn' , function(){ 
        id = $(this).data("id"); $("#item_data").html(''); $('#loader').show();
        $.ajax({
                url: "{{ route('admin.order.details') }}",
                method: 'post',
                data: {'id':id, "_token": "{{ csrf_token() }}",},
                success: function (result) {
                    var items = JSON.parse(result); 
                        let _html = '';
                        $(items.items).each(function(index, items){ 
                _html += `<tr><td> ${items.products.product_name} </td><td> ${items.item_price} </td><td> ${items.qty} </td><td> ${items.subtotal} </td><td> ${items.batch_code} </td><td> ${items.expiry_date } </td></tr>`;
                }); _html+='</tbody>';
                        $('#loader').hide();
                        $("#item_data").html(_html);
                },
                error: function (msg) {

                },
                
        }); 
    });
</script>
<script type="text/javascript">
    $(document).on('click','.order_id',function() {
        $('.order_id_inputs').append('<input type="hidden" name="order_id[]" value="'+$(this).val()+'">');
    });
     $(document).on('click','#change_status',function() {
        if ($(this).prop("checked") == true) {
            $('.is_status').val(1);     
        }else{
            $('.is_status').val(0);
        }
    });

    $('#export_csv').on('submit',function(){
        $('#csv_name').val($('#route').find(':selected').text());
    })
</script>
@endsection