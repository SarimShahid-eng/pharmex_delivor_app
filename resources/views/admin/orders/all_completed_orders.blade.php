@extends('layouts.admin')
@section('content')


<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">{{ $title }} Orders</h4>
        </div>
    </div>
</div>
<!--  <div class="row">
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
                                <form class="row align-items-end justify-content-center mb-2" enctype='multipart/form-data' id="filter_search">
                                    @csrf
                                    <input type="hidden" name="order_status" id="order_status" value="{{ $title }}">
                                   <div class="col-lg-12"> <br>
                                    <div class="form-group mb-3 ">
                                            <label for="order_no" class="sr-onlys mr-1">Users</label>
                                            <select id="user_type" name="user_type" parsley-trigger="change" class="form-control" required>
                                    <option value="employee">Employee</option>
                                    <option value="customer">Customer</option>
                                    
                                   
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
    </div> -->

<div class="row" id="card_data">
    @foreach($booked_orders AS $order)
    <div class="col-md-3 col-xl-3">
        <a href="{{ route('admin.completed_order',[hashids_encode($order->emp_code)]) }}">
        <div class="widget-rounded-circle card-box border">
            <div class="row border-bottom pb-2">
                <div class="col-10">
                    <div class="media">
                        <img src="{{ asset('admin_assets') }}/images/icons/user-icon.png" class="img-fluid mr-2"/>
                        <div class="media-body">
                            <h4 class="mt-2 mb-0">{{ $order->employee_name }}</h4>
                            <h5 class="mt-0" style="color: #6b6b6b;font-size: 14px;">Employee</h5>
                        </div>
                    </div>
                </div>

                <div class="col-2">
                    <div class="text-right mt-2">
                        <img src="{{ asset('admin_assets') }}/images/icons/info.png" class="img-fluid"/>
                    </div>
                </div>
            </div> <!-- end row-->
            <div class="row mt-3">
                <div class="col-6 text-center border-right">
                    <h4 class="mt-0 mb-1">{{ $order->total_orders }}</h4>
                    <p class="mb-0">No. of orders</p>
                </div>
                <div class="col-6 text-center">
                    <h4 class="mt-0 mb-1" style="color: #ff531e">Rs. {{ $order->total_amount }}</h4>
                    <p class="mb-0">Total Amount</p>
                </div>
            </div>
        </div> <!-- end widget-rounded-circle-->
        </a>
    </div>
    @endforeach
</div>
<div id="loader" style="text-align:center;display:none;">
    <div class="spinner">Loading...</div>
</div>

@endsection

@section('page-scripts')
<script type="text/javascript">
    $(document).on('submit', '#filter_search', function(e) {
      e.preventDefault();  
      var user_type = $('#user_type').val();
      var order_status = $('#order_status').val();
        $("#card_data").html(''); $('#loader').show();
        $.ajax({
                url: "{{ route('admin.orders.get_user_card') }}",
                method: 'post',
                data: {'user_type':user_type,'order_status':order_status, "_token": "{{ csrf_token() }}",},
                success: function (result) {
                    $('#loader').hide();
                    $("#card_data").html(result);
                },
                error: function (msg) {

                },
                
        });
    })
</script>
@endsection