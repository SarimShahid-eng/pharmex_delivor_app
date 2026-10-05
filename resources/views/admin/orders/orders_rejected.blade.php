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
                    <li class="breadcrumb-item active">Rejected Orders</li>
                </ol>
            </div>
            <h4 class="page-title">{{ $title }} ({{$customer_data->customer_name}})</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <!-- <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Pending Orders</h4>
            </div>
            <p class="sub-header">Following is the list of pending orders.</p>
            <div class="col-lg-12">
                <form action="{{ route('customer.pending_orders.search') }}">
                    <select class="custom-select col-lg-11" name="customer_id" id="customer_id">
                        
                    </select>
                    <input type="submit" value="Search" class="btn btn-primary">
                </form>
            </div> -->
            @if(count($orders[0]->order_detail) > 0)
                @foreach($orders AS $key=>$order)
                 @if(!empty($order->id) && count($order->order_detail)>0)
                    <div class="table_main">
                        <div class="float-left ml-1">
                            <h5>Order ID#{{ $order->id }}</h5>
                        </div>
                        <div class="float-right mr-1">
                            <h5>Date:{{ date('d-m-Y',strTotime($order->created_at)) }}</h5>
                        </div>
                        <table class="table dt_table table-bordered w-100 nowrap">
                            <thead>
                                <tr>
                                    {{-- <th width="20">S.No</th> --}}
                                    <th>Product Name</th>
                                    
                                    <th>Exp Date</th>
                                    <th>Batch Code</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                    {{-- <th>Order Status</th> --}}
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <?php $amount = 0; ?>
                                @foreach($order->order_detail AS $detail)
                                    <tr>
                                        <td>{{ $detail->products->product_name }}</td>
                                        
                                        <td>{{ date('d-m-Y',strtotime($detail->expiry_date)) }}</td>
                                        <td>{{ $detail->batch_code }}</td>
                                        <td>{{ $detail->item_price }}</td>
                                        <td>{{ $detail->qty }}</td>
                                        {{-- <th>{{ $order->status }}</th> --}}
                                        <td>{{ $detail->item_price * $detail->qty }}</td>
                                        {{-- <td>
                                            <form action="{{ route('checkbox') }}" class="ajaxForm" id="form">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $detail->id }}" name="order_return_details_id[]">
                                            </div>
                                        </td> --}}
                                    </tr>
                                    <?php $amount += $detail->qty * $detail->item_price ?>
                                @endforeach
                                <tr>
                                    <td colspan="5">Total</td>
                                    <td >{{ number_format($amount,2) }}</td>
                                </tr>
                                <input type="hidden" value="{{ $order->customer_id }}" name="customer_id">  
                            </tbody>
                        </table>
                    </div> 
                      
                  @endif  
                @endforeach
                <div class="col-lg-1 offset-lg-11">
                    {{-- <input type="submit" class="btn btn-primary mt-3" value="Approve"> --}}
                    {{-- <input type="submit" class="btn btn-primary mt-3" value="Reject" id="reject"> --}}
                </div>
            </form>
               @else
                   <h5 style="text-align: center;">No record found</h5> 
            @endif

           
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
    $('#reject').click(function(e){
        e.preventDefault();
        $("#form").attr("action","{{ route('checkbox.reject') }}");
        $('#form').submit();
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

