@if($type == 'rout')
@foreach($order_data as $order_data)
<div class="col-md-3 col-xl-3">
        <a href="{{ route('admin.view.orders', [hashids_encode($order_data->id), $order_data->order_status, 'rout']) }}">
        <div class="widget-rounded-circle card-box border">
            <div class="row border-bottom pb-2">
                <div class="col-10">
                    <div class="media">
                        <img src="{{ asset('admin_assets') }}/images/icons/mroute-icon.png" class="img-fluid mr-2"/>
                        <div class="media-body">
                            <h4 class="mt-2 mb-0">{{ ucfirst($order_data->region_name) }}</h4>
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
                    <h4 class="mt-0 mb-1">{{ $order_data->total_order }}</h4>
                    <p class="mb-0">No. of orders</p>
                </div>
                <div class="col-6 text-center">
                    <h4 class="mt-0 mb-1" style="color: #ff531e">Rs. {{ $order_data->total_amount }}</h4>
                    <p class="mb-0">Total Amount</p>
                </div>
            </div>
        </div> <!-- end widget-rounded-circle-->
        </a>
    </div>
    @endforeach
@else
@foreach($order_data as $order_data)
    <div class="col-md-3 col-xl-3">
        <a href="{{ route('admin.view.orders', [hashids_encode($order_data->employee_id), $order_data->order_status, $order_data->order_by]) }}">
        <div class="widget-rounded-circle card-box border">
            <div class="row border-bottom pb-2">
                <div class="col-10">
                    <div class="media">
                        <img src="{{ asset('admin_assets') }}/images/icons/user-icon.png" class="img-fluid mr-2"/>
                        <div class="media-body">
                            <h4 class="mt-2 mb-0">{{ ucfirst($order_data->employee_name) }}</h4>
                            <h5 class="mt-0" style="color: #6b6b6b;font-size: 14px;">{{ ucfirst($order_data->order_by) }}</h5>
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
                    <h4 class="mt-0 mb-1">{{ $order_data->total_orders }}</h4>
                    <p class="mb-0">No. of orders</p>
                </div>
                <div class="col-6 text-center">
                    <h4 class="mt-0 mb-1" style="color: #ff531e">Rs. {{ $order_data->total_amount }}</h4>
                    <p class="mb-0">Total Amount</p>
                </div>
            </div>
        </div> <!-- end widget-rounded-circle-->
        </a>
    </div>
    @endforeach
@endif    