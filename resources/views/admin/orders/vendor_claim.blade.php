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
                    <li class="breadcrumb-item active">Rejected orders</li>
                </ol>
            </div>
            <h4 class="page-title">{{ $title }}</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <a href="{{ route('admin.vendor_claim_orders.view') }}" class="btn btn-primary float-right">View Vendor Claim</a>
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Vendor Claim</h4>
            </div>
            <p class="sub-header">Following is the list of Vendor Claim.</p>
            <form action="{{ route('admin.vendor_claims.result') }}" method="get" id="form">
                <div class="row">
                <div class="col-lg-4">
                    <label for="">Companies</label>
                    <select name="company" id="company_id" class="form-control" required>
                        <option value="" selected disabled>Select Company</option>
                        @foreach($companies AS $company)
                            <option value="{{ $company->hashid }}" @if(@$company_id == $company->id) selected @endif >{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <label>from</label>
                    <input type="date" class="form-control" name="date_from" required id="date_from" value="{{ @$date_from }}">
                </div>
                <div class="col-lg-3">
                    <label>To</label>
                    <input type="date" class="form-control" name="date_to" required id="date_to" value="{{ @$date_to }}">
                </div>
                <div class="col-lg-2">
                    <input type="submit" value="Search" class="btn btn-primary" style="margin-top: 29px;">
                </div>    
            </div>
            </form>
            <br><br>
                
                    <div class="table_main">
                        @if(isset($claims) && !empty($claims[0])) 
                            <button class="btn btn-primary float-right mb-2" id="check_all">Check All</button>
                        @endif
                        
                        <table class="table dt_table table-bordered w-100 nowrap" id="vendor_table">
                            <thead>
                                <tr>
                                    <th width="20">S.No</th>
                                    <th>Product Name</th>
                                    <th>Batch</th>
                                    <th>Expiry Date</th>
                                    <th>Product Code</th>
                                    <th>Lifted Stock</th>
                                    <th>Unit Price</th>
                                    <th>Subotal</th>
                                    <th style="width: 130px">Qty</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(isset($claims) && !empty($claims[0]))
                                @foreach($claims AS $key => $claim)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$claim->product_name}}</td>
                                        <td>{{ $claim->batch_code }}</td>
                                        {{-- <td>{{ \CommonHelpers::date_format_custom($claim->expiry_date) }}</td> --}}
                                        <td>{{ date('d-m-Y',strtotime($claim->expiry_date)) }}</td>
                                        <td>{{ $claim->product_code }}</td>
                                        <td>{{ $claim->qty }}</td>
                                        <td>{{ $claim->item_price }}</td>
                                        <td>{{ $claim->subtotal }}</td>
                                        <td><input type="number" class="form-control add_qty" id="add_qty" name="add_qty[]" value="0" min="0"></td>
                                        {{-- <td>{{$order->customer->regions->region_name}}</td>
                                        <td>{{$order->customer->town->town}}</td> --}}
                                        {{-- <td>{{\CommonHelpers::date_format_custom($order->created_at)}}</td> --}}
                                        {{-- <td>
                                        <a  name="edit" href="{{route('customer.rejected_orders.search','id='.$order->customer->hashid)}}" class="btn btn-outline-primary btn-rounded waves-effect waves-light " ><i class="fas fa-eye"></i></a>
                                        </td> --}}
                                        <td><input type="checkbox" name="claim_check[]" value="{{ hashids_encode($claim->product_id) }}" batch_code="{{ $claim->batch_code }}"></td>
                                    </tr>
                                @endforeach
                            @else
                               <tr>
                                 <td colspan="8" align="center">No Record</td>
                               </tr>
                            @endif

                            </tbody>
                        </table>
                        @if(isset($claims) && !empty($claims[0])) 
                        <button type="button" class="btn btn-primary float-right" id="export">Exports</button>
                        @endif
                        @if(isset($claims) && !empty($claims[0])) 
                        <button type="button" class="btn btn-primary float-right mr-2" id="vendor_claim">Vendor Claim</button>
                        @endif
                    </div> 
               
                <div class="col-lg-1 offset-lg-11">
                    {{-- <input type="submit" class="btn btn-submit"> --}}
                    {{-- <input type="submit" class="btn btn-primary mt-3"> --}}
                </div>
            </form>
            <form action="" id="export_form">
                <input type="hidden" name="batch_codes">
                <input type="hidden" name="product_ids">
            </form>

            <form action="" id="vendor_claim_form" class="ajaxForm">
                <input type="hidden" name="batch_codes">
                <input type="hidden" name="product_ids">
                <input type="hidden" name="company">
                <input type="hidden" name="add_qty">
            </form>
           
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
        $('#company_id').select2();
    });//select 2 end

    $('#export').click(function(){
        
        var product_ids = [];
        var batch_codes = [];
        var claim_check = $('input[name="claim_check[]"]:checked').length;
        // var claim_check = $('input[name="claim_check[]"]').is(':checked').length;
       
        if(claim_check > 0){
            $('input[name="claim_check[]"]:checked').each(function(){
                //assign values to array
                product_ids.push($(this).val());
                batch_codes.push($(this).attr('batch_code'));
            });
            //assign the values to hidden form and submit
            $('#export_form input[name="batch_codes"]').val(batch_codes);
            $('#export_form input[name="product_ids"]').val(product_ids);
            $('#export_form').attr("action","{{ route('admin.vendor_claims.export') }}");
            $('#export_form').submit();

        }else{
            alert('Please check the boxes');
        }
    });
    //check all
    $('#check_all').click(function(){
        $('input[name="claim_check[]"]').prop('checked',true);
    });
    //vendor claim
    $('#vendor_claim').click(function(){
        var product_ids = [];
        var batch_codes = [];
        var add_qty = [];
        var claim_check = $('input[name="claim_check[]"]:checked').length;
        
        if(claim_check > 0){
            $('input[name="claim_check[]"]:checked').each(function(){
                //assigm values to array;
                product_ids.push($(this).val());
                batch_codes.push($(this).attr('batch_code'));

            });
            $('.add_qty').each(function(){
                add_qty.push($(this).val());
            });
            // console.log(add_qty);
            // return false;
            //assign values to hidden forma and submit
            $('#vendor_claim_form input[name="batch_codes"]').val(batch_codes);
            $('#vendor_claim_form input[name="product_ids"]').val(product_ids);
            $('#vendor_claim_form input[name="company"]').val($('#company_id').val());
            $('#vendor_claim_form input[name="add_qty"]').val(add_qty);
            $('#vendor_claim_form').attr("action","{{ route('admin.vendor_claim_orders') }}");
            $('#vendor_claim_form').submit();
            
        }else{
            alert('Please check the boxes');
        }
    })
</script>
@endsection

