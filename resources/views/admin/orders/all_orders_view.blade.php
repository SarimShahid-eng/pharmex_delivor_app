@extends('layouts.admin')
@section('content')

<style>
    table {
     table-layout: fixed;
    }
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
    .dt_table tr td{max-width: 120px;white-space: break-spaces !important;}
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">orders</li>
                </ol>
            </div>
            <h4 class="page-title">All {{ $title }} Orders</h4>
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
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Orders</h4>
                
            </div>
            <p class="sub-header">Following is the list of all the orders.</p>
            <div class="col-lg-12 ">
           
                <form action="{{ route('admin.view.order_post', [ $user_id,$title,$user_type]) }}" method='POST' >
                    @csrf
                    <div class="row justify-content-center ">                   
                        <div class="col-sm-3 d-flex" >
                            <input type="text"  class="form-control human_datepicker" placeholder="Date From" onfocus="(this.type='date')"  name="date_from"  value="{{ @$_GET['date_from'] }}">
                        </div>                    
                        <div class="col-sm-3 d-flex" >
                            <input type="text" placeholder="Date To" class="form-control human_datepicker" name="date_to" onfocus="(this.type='date')" value="{{ @$_GET['date_to'] }}" >
                        </div> 
                        <div >
                        
                        <button type="submit" class="btn btn-primary" >Sumbit</button>
                              
                        </div> 
                    </div>  
                </form>
            </div>
            <div class="table_main">
                
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="5%">S.No</th>
                        <th width="11%">Customer Name</th>
                        <th width="7%">Town</th>
                        <th width="7%">Rout</th>
                        <th width="11%">Employee Name</th>
                        <th width="7%">Order <br> Price</th>
                        <th width="7%">Order <br> Date</th> 
                        <th width="7%">Order <br> Time</th>
                        <th width="7%">Delivery <br>Date</th>
                        <th width="7%">Status</th>
                        <th width="7%">Order <br> Status</th>
                        <th width="7%">Order By</th>
                        <th width="10%">Action</th>
                    </tr>
                    <tbody>
                            
                         @foreach($locations as $k => $location)
                                <tr>
                                    <td>{{$k + 1}}</td>
                                    <td>{{$location->customer_name}}</td>
                                    <td>{{$location->town}}</td>
                                    <td>{{$location->region_name}}</td>
                                    <td>{{$location->employee_name}}</td>
                                    <td>{{$location->total_amount}}</td>
                                    <td>{{$location->order_date}}</td>
                                    <td>{{$location->created_at}}</td>
                                    <td>{{$location->delivery_date}}</td>
                                    <td>{{$location->status}}</td>
                                    <td>{{$location->order_status}}</td>
                                    <td>{{$location->order_by}}</td>
                                    <td class="d-flex justify-content-between ">
                                        <a type="button" name="edit" href="#" class="btn btn-outline-primary btn-rounded waves-effect waves-light model_btn" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="{{$location->hashid}}"><i class="fas fa-eye"></i></a>
                                        <a type="button" name="edit" href="{{route('admin.order.update', $location->hashid)}}" class="btn btn-outline-primary btn-rounded waves-effect waves-light model_btn"><i class="fas fa-pencil-alt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                                
                            
                        </tbody>
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
  
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    let table = new DataTable('#laravel_datatable');
    
});
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

