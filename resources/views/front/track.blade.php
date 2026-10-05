@extends('layouts.frontend')
@section('content')
<!-- Breadcroumbs start -->
<div class="wshipping-content-block wshipping-breadcroumb inner-bg-1">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-7">
                <h1>TRACKING</h1>
            </div>
        </div>
    </div>
</div>
<!-- Breadcroumbs end -->

<!-- About content start -->
<div class="wshipping-content-block pt-4">
    <div class="container">
        <div class="row flex-lg-row-reverse">
            <div class="col-12">
                <div class="right-block mt-4">
                    <div class="inner-pagetitle text-center mt-5">
                        <h2 class="heading2-border">Track Your <span>Shipment</span></h2>
                        <p>Enter a tracking number, and get tracking results.</p>
                    </div>

                    <div class="track_form">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-9 offset-md-2">
                                    <form action="{{ route('front.track') }}" method="get">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="order_id" placeholder="Enter Your Tracking Id" value="{{ request('order_id') ?? 'ORD-' }}">
                                            </div>
                                            <div class="col-md-3">
                                                <button class="banner-searchbtn"><img src="{{ asset('frontend_assets') }}/images/track_icon.png"> Track</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @if(isset($order) && newCount($order_details) > 0)
                            <div class="track-result mt-5">
                                <div class="track-result-block">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="track-result-id col-12 col-lg-4"><strong style="font-size: 75%">Order No:</strong> {{ $order->order_no }}</div>
                                            <div class="track-result-id col-12 col-lg-4"><strong style="font-size: 75%">Airway Company:</strong> {{ $order->cargo_service->name ?? '-' }}</div>
                                            <div class="track-result-id col-12 col-lg-4">
                                                <strong style="font-size: 75%">Sending Mode:</strong> {{ $order->sending_mode ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="track-status">
                                        <div class="row">
                                            <div class="col-12 col-lg-4"><b>Sender Name:</b> {{ $order->sender_name }}</div>
                                            <div class="col-12 col-lg-4"><b>Receiver Name:</b> {{ $order->receiver_name }}</div>
                                            <div class="col-12 col-lg-4"><b>Booking Date:</b> {{ get_date($order->created_at) }}</div>
                                        </div>
                                    </div>
                                    <div class="track-result-bar">
                                        <ul class="track-progress">
                                            <li class="icon-confirm-roder track-active">
                                                <h5 class="m-0">Booked</h5>
                                                <p class="text-muted">{{ get_date($order->created_at) }}</p>
                                            </li>
                                            <li class="icon-transit {{ isset($order_details['assigned']->created_at) ? 'track-active' : '' }}">
                                                <h5 class="m-0">In Transit</h5>
                                                <p class="text-muted">{{ isset($order_details['assigned']->created_at) ? get_date($order_details['assigned']->created_at) : '' }}</p>
                                            </li>
                                            <li class="icon-cubes {{ isset($order_details['received']->created_at) ? 'track-active' : '' }}">
                                                <h5 class="m-0">Pickup</h5>
                                                <p class="text-muted">{{ isset($order_details['received']->created_at) ? get_date($order_details['received']->created_at) : '' }}</p>
                                            </li>
                                            <li class="icon-check-circle {{ isset($order_details['shipped']->created_at) ? 'track-active' : '' }}">
                                                <h5 class="m-0">Shipped</h5>
                                                <p class="text-muted">{{ isset($order_details['shipped']->created_at) ? get_date($order_details['shipped']->created_at) : '' }}</p>
                                            </li>
                                        </ul>
                                    </div>
                                    @if($order->status == 'shipped')
                                    <div class="shipped_details">
                                        <div class="card">
                                            <div class="card-block">
                                                <h3 class="card-title">Airway Shipping Details</h3>
                                                <h4 class="card-title">
                                                    <small class="text-muted">Airway Bill No:</small>
                                                    <span class="text-primary">{{ $order->airway_bill_no }}</span>
                                                    <button class="btn btn-link btn-sm" id="clipboard_copy_btn" data-title="Click here to copy" data-toggle="tooltip" data-clipboard-text="{{ $order->airway_bill_no }}">
                                                        <i class="fa fa-clone"></i>
                                                    </button>
                                                </h4>
                                                <h6 class="card-subtitle mb-2 text-muted border-bottom pb-2">
                                                    <small class="text-muted">Via:</small>
                                                    <a href="{{ $order->cargo_service->url }}" target="_blank" class="card-link">{{ $order->cargo_service->name }}</a>
                                                </h6>
                                                <div class="card-text">
                                                    {{ $order->cargo_service->desc }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @else
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-center">
                                        <div class="inner-pagetitle text-center mt-5">
                                            <h2 class="m-0">Order Not <span>Found</span></h2>
                                            <h2 class="heading2-border mt-2 text-muted" style="font-size:20px">Invalid Order No</h2>
                                            <img src="{{ asset('frontend_assets') }}/images/order_not_found.png" alt="Order No Invalid" width="300">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About content end -->
@endsection

@section('page-scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
<script src="{{ asset('frontend_assets') }}/js/sweetalert2.min.js"></script>
<script>
    var clipboard = new ClipboardJS('.btn');
    clipboard.on('success', function(e) {
        e.clearSelection();
        var opts = {
            title: "Copied!!!",
            type: "success",
            confirmButtonClass: "btn btn-confirm mt-2",
            timer: 750
        };
        Swal.fire(opts);
    });

    clipboard.on('error', function(e) {
        var opts = {
            title: "Copy Failed!!!",
            text: "Please try again",
            type: "error",
            confirmButtonClass: "btn btn-confirm mt-2",
        };
        Swal.fire(opts);
    });
</script>
@endsection