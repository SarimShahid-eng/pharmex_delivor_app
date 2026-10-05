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

    .card-body{position: relative;padding-bottom: 65px;}
    .btn-book:hover{background-color:#2dad5d !important}
    .btn-pending:hover{background-color:#d8752c !important}
    .btn-cancel:hover{background-color:#dc464d !important}
    .btn-process:hover{background-color:#3869d6 !important}
    .text-white {font-weight: unset!important ;}
</style>

<!--<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">orders</li>
                </ol>
            </div>
            <h4 class="page-title">All Orders</h4>
        </div>
    </div>
</div>-->
    <!-- <div class="row">
        <div class="col-lg-12">
            <div class="card custom_filters">
                <div class="card-header bg-dark text-white">
                    <div class="card-widgets">
                        <a data-toggle="collapse" href="#filters_div" role="button" aria-controls="filters_div" class="collapsed" aria-expanded="false"><i class="mdi mdi-minus"></i></a>
                    </div>
                    <h4 class="card-title mb-0 text-white">Filter</h4>
                </div>
                <div id="filters_div" class="card-body collapse" style="">
                   <div class="row">
                        <div class="col-lg-12">
                            <div class="card-box">
                                <h4 class="header-title m-t-0">Region Filter</h4>
                                <form method="post" class="row align-items-end justify-content-center mb-2 ajaxForm" enctype='multipart/form-data' id="filter_search">
                                    @csrf

                                   <div class="col-lg-12"> <br>
                                    <div class="form-group mb-3 ">
                                            <label for="order_no" class="sr-onlys mr-1">Regions</label>
                                            <select id="region_id" name="region_id" parsley-trigger="change" class="form-control" required>
                                    <option value="">Select Regions</option>
                                    
                                   
                                </select>
                                           
                                    </div>
                                        <div class="form-group mb-3 text-right">

                                            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>

                                        </div>
                                 </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 -->
<div class="row">
    <div class="col-lg-12">
         <a class="d-inline-block btn btn-primary waves-effect waves-light" href="{{ route('admin.files') }}">File Import/Export</a>
        <div class="card-box morders" style="background-color: transparent">
            <div class="row row-eq-height">
                <div class="col-md-3">
                    <div class="card" style="border-radius: 15px; height: 100%">
                        <div class="card-header" style="background-color: #2ead5e;border-radius: 15px 15px 0 0;">
                            <div class="row">
                                <div class="col-sm-8">
                                  <h1 class="text-white">{{ $booked_order }}</h1>
                                  <h4 class="text-white">Booked Orders</h4>
                                </div>
                                <div class="col-sm-4">
                                  <img src="{{ asset('admin_assets') }}/images/icons/page-orders.png" class="img-fluid mt-2">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mb-1">Recent Activity</h5>
                            <ul class="list-group list-group-flush">
                            @foreach($booked_order_data as $booked_order_data)   
                                <li class="list-group-item px-0 border-top-0">Order#{{ $booked_order_data->id }} Booked by {{ $booked_order_data->order_by }}...</li>
                            @endforeach    
                            </ul>
                            <a href="{{ route('admin.view_order', 'booked') }}" class="btn btn-book btn-block mt-2" style="background-color: #000;color: #fff;border-radius: 25px;padding: 10px 15px;font-weight: bold;position: absolute;bottom: 20px;width: 84%;left: auto;right: auto;">View all</a>
                        </div>
                    </div>
                </div>
                 <div class="col-md-3">
                    <div class="card" style="border-radius: 15px; height: 100%">
                        <div class="card-header" style="background-color: #2c91d8;border-radius: 15px 15px 0 0;">
                            <div class="row">
                                <div class="col-sm-8">
                                  <h1 class="text-white">{{ $processed_order }}</h1>
                                  <h4 class="text-white">Processing Orders</h4>
                                </div>
                                <div class="col-sm-4">
                                  <img src="{{ asset('admin_assets') }}/images/icons/page-orders.png" class="img-fluid mt-2">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mb-1">Recent Activity</h5>
                            <ul class="list-group list-group-flush">
                            @foreach($processed_order_data as $processed_order_data)   
                                <li class="list-group-item px-0 border-top-0">Order#{{ $processed_order_data->id }} Booked by {{ $processed_order_data->order_by }}...</li>
                            @endforeach 
                            </ul>
                            <a href="{{ route('admin.view_order', 'processed') }}" class="btn btn-pending btn-block mt-2" style="background-color: #000;color: #fff;border-radius: 25px;padding: 10px 15px;font-weight: bold;position: absolute;bottom: 20px;width: 84%;left: auto;right: auto;">View all</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card" style="border-radius: 15px; height: 100%">
                        <div class="card-header" style="background-color: #d8752c;border-radius: 15px 15px 0 0;">
                            <div class="row">
                                <div class="col-sm-8">
                                  <h1 class="text-white">{{ $completed_order }}</h1>
                                  <h4 class="text-white">Completed Orders</h4>
                                </div>
                                <div class="col-sm-4">
                                  <img src="{{ asset('admin_assets') }}/images/icons/page-orders.png" class="img-fluid mt-2">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mb-1">Recent Activity</h5>
                            <ul class="list-group list-group-flush">
                            @foreach($completed_order_data as $completed_order_data)   
                                <li class="list-group-item px-0 border-top-0">Order#{{ $completed_order_data->id }} Booked by {{ $completed_order_data->order_by }}...</li>
                            @endforeach 
                            </ul>
                            <a href="{{ route('admin.view_completed_order', 'completed') }}" class="btn btn-pending btn-block mt-2" style="background-color: #000;color: #fff;border-radius: 25px;padding: 10px 15px;font-weight: bold;position: absolute;bottom: 20px;width: 84%;left: auto;right: auto;">View all</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card" style="border-radius: 15px; height: 100%">
                        <div class="card-header" style="background-color: #dc464d;border-radius: 15px 15px 0 0;">
                            <div class="row">
                                <div class="col-sm-8">
                                  <h1 class="text-white">{{ $cancel_order }}</h1>
                                  <h4 class="text-white">Cancelled Orders</h4>
                                </div>
                                <div class="col-sm-4">
                                  <img src="{{ asset('admin_assets') }}/images/icons/page-orders.png" class="img-fluid mt-2">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mb-1">Recent Activity</h5>
                            <ul class="list-group list-group-flush">
                            @foreach($cancel_order_data as $cancel_order_data)   
                                <li class="list-group-item px-0 border-top-0">Order#{{ $cancel_order_data->id }} Booked by {{ $cancel_order_data->order_by }}...</li>
                            @endforeach 
                            </ul>
                            <a href="{{ route('admin.view_order', 'cancel') }}" class="btn btn-cancel btn-block mt-2" style="background-color: #000;color: #fff;border-radius: 25px;padding: 10px 15px;font-weight: bold;position: absolute;bottom: 20px;width: 84%;left: auto;right: auto;">View all</a>
                        </div>
                    </div>
                </div>
                
            </div>
            <!--<div class="d-flex align-items-center justify-content-between">
                <h1>Orders</h1>
            </div>
            <p class="sub-header">Following is the list of all the orders.</p>
            <div class="table_main">
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr style="background-color: #dee9e5;">
                        <th width="20">S.No</th>
                        <th>Customer Name</th>
                        <th>Employee Name</th>
                        <th>Order Price</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                        <th>Status</th>
                        <th>Order Status</th>
                        <th>Order By</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>-->
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
@endsection

