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
                    <li class="breadcrumb-item active">Notification
                </ol>
            </div>
            <h4 class="page-title">{{ $title }}</h4>
        </div>
    </div>
</div>
    
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Notification</h4>
            </div>
            <p class="sub-header">List Of All Notification </p>
            <div class="col-lg-12">
           
                <form action="{{ route('admin.notification.all_notifications') }}" method='GET' >
                    <div class="row">                   
                        <div class="col-sm-2" style="padding-top: 30px;">
                            <select name="search_customer"  class="form-control" required>
                                <option value="">Select Customer</option>
                                
                                @foreach($customers as $customer)
                                    <option value="{{$customer->hashid}}" @if(@$customer_id == $customer->id) selected @endif >{{ $customer->customer_name }}</option>
                                @endforeach
                                </select>
                        </div>                     
                        <div class="col-sm-3 d-flex" style="padding-top: 30px;">
                            <input type="date" placeholder="Date From" class="form-control human_datepicker" name="date_from"  value="{{ @$_GET['date_from'] }}">
                        </div>                    
                        <div class="col-sm-3 d-flex" style="padding-top: 30px;">
                            <input type="date" placeholder="Date To" class="form-control human_datepicker" name="date_to" value="{{ @$_GET['date_to'] }}" >
                        </div> 
                        <div >
                        
                        <button type="submit" class="btn btn-primary" style="margin-top: 30px;">Search</button>
                              
                        </div> 
                    </div>  
                </form>
            </div>
            <br><br>
                <div class="table_main">
                    <table class="table dt_table table-bordered w-100 nowrap">
                        <thead>
                            <tr>
                                <th width="20">S.No</th> 
                                <th>Customer Name</th>
                                <th>Rout</th>
                                <th>Town</th>
                                <th>Order Amount</th>
                                <th>Status</th>
                                <th>Order Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach($Records as $k => $data)
                                <tr>
                                    <td>{{$k + 1}}</td>
                                    <td>{{$data->customer_name}}</td>
                                    <td>{{$data->region_name}}</td>
                                    <td>{{$data->town}}</td>
                                    <td>{{$data->total_amount}}</td>
                                    <td>{{$data->status}}</td>
                                    <td>{{$data->created_at}}</td>
                                    <td>
                                        <a href="{{'orders/customer/search_pending_orders/'.hashids_encode($data->customer_id)}}" class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i class="icon-pencil"></i></a>
                                    </td>    
                                </tr>
                            @endforeach
                                  
                            {{-- @else
                                <h5>EMPTYs</h5>--}} 
                            
                        </tbody>
                    </table>
                    {{ $Records->links() }}
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

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#customer_id').select2();
    });
</script>
<!-- <script>
$(document).ready(function () {

    $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 0,
        lengthMenu: [10, 20, 50, 100, 200, 500],
        ajax: "{{ route('admin.orders_return.list') }}",
        columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'customer.customer_name', name: 'customer.customer_name'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'order_date', name: 'order_date'},
            {data: 'approval_date', name: 'approval_date'},
            {data: 'status',name: 'status'},
            {data: 'action',name: 'action',orderable: false}
        ]
    });
});
</script> -->
<!-- <script type="text/javascript">
    $(document).on('click' , '.model_btn' , function(){
        id = $(this).data("id"); $("#item_data").html(''); $('#loader').show();
        $.ajax({
                url: "{{ route('admin.orderRetuen.details') }}",
                method: 'post',
                data: {'id':id, "_token": "{{ csrf_token() }}",},
                success: function (result) {
                    var items = JSON.parse(result); 
                        let _html = '';
                        $(items.items).each(function(index, items){ 
                _html += `<tr><td> ${items.products.product_name} </td><td> ${items.item_price} </td><td> ${items.qty} </td><td> ${items.subtotal} </td><td> ${items.expiry_date } </td></tr>`;
                }); _html+='</tbody>';
                        $('#loader').hide();
                        $("#item_data").html(_html);
                },
                error: function (msg) {

                },
                
        }); 
    });
</script> -->
@endsection

