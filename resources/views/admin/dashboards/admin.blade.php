@extends('layouts.admin')

@section('content')

<style>

.hk_custom .widget-rounded-circle {
    padding: 0;
    padding-top: 15px;
    padding-left: 5px;
    padding-right: 5px;
}
.hk_custom h3 {
    font-size: 18px;
    margin-bottom: 4px;
}
.hk_custom p {
    font-size: 12px;
}

#revenue_chart,
#orders_chart{
    max-height: 350px;
    min-height: 350px;
}
</style>

<!-- start page title
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div> -->
<div class="row">
    <div class="col-12">
        <a class="d-inline-block mb-2 btn btn-primary waves-effect waves-light" href="{{ route('admin.files') }}" style="background-color: #e85528;">File Import/Export</a>
    </div>

    <div class="col-md-8">
        <div class="row">
            <div class="col-md-3">
                <a href="{{ route('admin.view_order', 'booked') }}">
                    <div class="widget-rounded-circle card-box" style="background: #2dad5d;border-radius: 20px;">
                        <div class="">
                            <img src="{{ asset('admin_assets') }}/images/icons/page-orders.png" />

                            <h5 class="text-white font-weight-normal mt-3">Booked Request</h5>
                            <h2 class="mt-1 text-white mb-0"><span data-plugin="counterup">{{ $data['booked_order'] }}</span></h2>
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </a>
            </div> <!-- end col-->

            <div class="col-md-3">
                <a href="{{ route('admin.view_order', 'processed') }}">
                    <div class="widget-rounded-circle card-box" style="background: #ff9e00;border-radius: 20px;">
                        <div class="">
                            <img src="{{ asset('admin_assets') }}/images/icons/page-orders.png" />

                            <h5 class="text-white font-weight-normal mt-3">Processing Orders</h5>
                            <h2 class="mt-1 text-white mb-0"><span data-plugin="counterup">{{ $data['processed_order'] }}</span></h2>
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </a>   
            </div> <!-- end col-->

            <div class="col-md-3">
                <a href="{{ route('admin.view_order', 'cancel') }}">
                    <div class="widget-rounded-circle card-box" style="background: #dc464d;border-radius: 20px;">
                        <div class="">
                            <img src="{{ asset('admin_assets') }}/images/icons/page-orders.png" />

                            <h5 class="text-white font-weight-normal mt-3">Cancelled Orders</h5>
                            <h2 class="mt-1 text-white mb-0"><span data-plugin="counterup">{{ $data['cancel_order'] }}</span></h2>
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </a>   
            </div> <!-- end col-->

            <div class="col-md-3">
                <a href="{{ route('admin.view_order', 'completed') }}">
                    <div class="widget-rounded-circle card-box" style="background: #3869d6;border-radius: 20px;">
                        <div class="">
                            <img src="{{ asset('admin_assets') }}/images/icons/page-orders.png" />

                            <h5 class="text-white font-weight-normal mt-3">Completed Orders</h5>
                            <h2 class="mt-1 text-white mb-0"><span data-plugin="counterup">{{ $data['completed_order'] }}</span></h2>
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </a>    
            </div> <!-- end col-->
        </div>
        
        <div class="rows">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Order Statistics</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <h5>Booked Orders</h5>
                        </div>
                        @php
                        if($data['booked_order'] == 0){
                            $a = 0;
                        }else{
                            $a = round($data['booked_order']/$data['total_order']*100);
                        }
                        @endphp
                        <div class="col-md-7">
                            <div class="progress mt-2 position-relative" style="background-color: #eaebf4; width: 90%; height:8px; display: inline-flex; margin-right: 0; overflow: visible">
                                <span style="position: absolute; left: -2px; width: 20px; height: 20px; border-radius: 50%; background-color: #00b9ac; top: -6px;"></span>
                                <div class="progress-bar" role="progressbar" style="width: {{ $a }}%; background: #00b9ac" aria-valuemax="100"></div>
                            </div>
                             <span style="display: inline-block;margin-left: 10px; color: #eaebf4">{{ $a }}%</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <h5>Processing orders</h5>
                        </div>
                        @php
                        if($data['processed_order'] == 0){
                            $b = 0;
                        }else{
                            $b = round($data['processed_order']/$data['total_order']*100);
                        }
                        @endphp
                        <div class="col-md-7">
                            <div class="progress mt-2 position-relative" style="background-color: #eaebf4; width: 90%; height:8px; display: inline-flex; margin-right: 0; overflow: visible">
                                <span style="position: absolute; left: -2px; width: 20px; height: 20px; border-radius: 50%; background-color: #ff9e00; top: -6px;"></span>
                                <div class="progress-bar" role="progressbar" style="width: {{$b}}%; background: #ff9e00" aria-valuemax="100"></div>
                            </div>
                             <span style="display: inline-block;margin-left: 10px; color: #eaebf4">{{$b}}%</span>
                        </div>
                    </div>

                    

                    <div class="row">
                        <div class="col-md-3">
                            <h5>Cancelled Orders</h5>
                        </div>
                        @php
                        if($data['cancel_order'] == 0){
                            $c = 0;
                        }else{
                            $c = round($data['cancel_order']/$data['total_order']*100);
                        }
                        @endphp
                        <div class="col-md-7">
                            <div class="progress mt-2 position-relative" style="background-color: #eaebf4; width: 90%; height:8px; display: inline-flex; margin-right: 0; overflow: visible">
                                <span style="position: absolute; left: -2px; width: 20px; height: 20px; border-radius: 50%; background-color: #dc464d; top: -6px;"></span>
                                <div class="progress-bar" role="progressbar" style="width: {{$c}}%; background: #dc464d" aria-valuemax="100"></div>
                            </div>
                             <span style="display: inline-block;margin-left: 10px; color: #eaebf4">{{$c}}%</span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <h5>Completed Orders</h5>
                        </div>
                        @php
                        if($data['completed_order'] == 0){
                            $d = 0;
                        }else{
                            $d = round($data['completed_order']/$data['total_order']*100);
                        }
                        @endphp
                        <div class="col-md-7">
                            <div class="progress mt-2 position-relative" style="background-color: #eaebf4; width: 90%; height:8px; display: inline-flex; margin-right: 0; overflow: visible">
                                <span style="position: absolute; left: -2px; width: 20px; height: 20px; border-radius: 50%; background-color: #1978b2; top: -6px;"></span>
                                <div class="progress-bar" role="progressbar" style="width: {{$d}}%; background: #1978b2" aria-valuemax="100"></div>
                            </div>
                             <span style="display: inline-block;margin-left: 4px; color: #eaebf4">{{$d}}%</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <button class="btn btn-primary" id="version">Version ({{ @$data['version']->android_version }})</button>
    </div>

    <div class="col-md-4">
        <div class="rows">
            <div class="card" style="border-radius: 10px 10px 0 0;">
                <div class="card-header text-white" style="background-color: #e85528;padding: 12px 10px 12px 20px;border-radius: 10px 10px 0 0;">
                    <span class="font-weight-bold" style="font-size: 16px;">Notifications</span> 
                    <!-- <a href="#" class="float-right text-white pr-2">View All</a> -->
                </div>
                <div class="card-body p-0" style="overflow: auto;max-height: 400px;height: 400px;">
                    <ul class="list-unstyled pt-2">
                    @if(count($data['all_orders']) > 0)
                    @foreach($data['all_orders'] as $all_orders)    
                        @if(!empty($all_orders->order_detail[0]))
                        <li class="media border-bottom px-3 py-2">
                            <div class="media-body">
                                {{-- <h5 class="mt-0 font-weight-bold mb-0">Order#{{$all_orders->id}} Order by {{$all_orders->order_by}}</h5> --}}
                                <h5 class="mt-0 font-weight-bold mb-0" style="font-size:13px">{{$all_orders->customer->customer_name}} From ({{ $all_orders->customer->town->town }}) ({{ (!empty($all_orders->customer->ph_num)) ? $all_orders->customer->ph_num : '--' }})</h5>
                                <p>{{ \CommonHelpers::date_time_full($all_orders->created_at) }} </p>
                            </div>
                            <a type="button" class="btn btn-default model_btn" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="{{ $all_orders->hashid }}" style="background-color: #e4e8e7;color: #6b6b6b;border-radius: 25px;padding: 8px 20px;">View</a>
                            <!-- <a type="button" name="edit" href="#" class="btn btn-outline-primary btn-rounded waves-effect waves-light model_btn" data-toggle="modal" data-target=".bs-example-modal-lg" data-id="mYa4o0gGBQ7ER928zxMp9nJde"><i class="fas fa-eye"></i></a> -->
                        </li>
                        @endif
                    @endforeach
                    @else
                        <li class="media border-bottom px-3 py-2">
                            <div class="media-body">
                                <h5 class="mt-0 font-weight-bold mb-0 text-center">No order!</h5>
                                <!-- <p>Feb 03, 2021, 5:00 PM </p> -->
                            </div>
                        </li>   
                    @endif
                    </ul>

                </div>
            </div>
        </div>
    </div>
<!-- end page title -->

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

<!--VERSION MODAL-->
<div class="modal fade" id="version_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Version</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.android_version.update') }}" method="POST" class="ajaxForm">
                    @csrf
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Android Version</label>
                    <input type="number" class="form-control" id="android_version" name="android_version" step="any" value="{{ @$data['version']->android_version }}" required>
                </div>
                
            </div>
            <div class="modal-footer">
                <input type="hidden" name="version_id" value="{{ @$data['version']->hashid }}">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="@if(isset($data['version']->id)) update @else Add @endif">
                
            </div>
        </form>
        </div>
    </div>
</div>            

@endsection

@section('page-scripts')

<!-- Plugins js-->
<script src="{{ asset('admin_assets') }}/libs/flatpickr/flatpickr.min.js"></script>
<script src="{{ asset('admin_assets') }}/libs/jquery-knob/jquery.knob.min.js"></script>
<script src="{{ asset('admin_assets') }}/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="{{ asset('admin_assets') }}/libs/chart-js/chart-js.min.js"></script>

<!-- Dashboar 1 init js-->
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

    $('#version').click(function(){
        $('#version_modal').modal('show');
    });
</script>
@endsection