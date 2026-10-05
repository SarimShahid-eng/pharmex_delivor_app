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
                    <li class="breadcrumb-item active">Vendor Claim order Details</li>
                </ol>
            </div>
            <h4 class="page-title">{{ $title }} ({{ $company_name->childCompany->company_name }})</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Order ID#{{ @hashids_decode($order_id) }}</h4>
            </div>
            {{-- <p class="sub-header">Following is the list of vendor claim orders.</p> --}}
            {{-- <form action="{{ route('admin.vendor_claim_orders.view') }}" method="get">
             <div class="row">
                <div class="col-lg-4">
                    <label for="">Companies</label>
                    <select name="company_id" id="company_id" class="form-control" required>
                        <option value="" selected disabled>Select Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->hashid }}">{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <label>from</label>
                    <input type="date" class="form-control" name="date_from">
                </div>
                <div class="col-lg-3">
                    <label>To</label>
                    <input type="date" class="form-control" name="date_to">
                </div>
                <div class="col-lg-2">
                    <input type="submit" value="Search" class="btn btn-primary" style="margin-top: 29px;">
                </div>    
            </div>
            </form> --}}
            <br><br>
              
                    <div class="table_main">
                        <table class="table dt_table table-bordered w-100 nowrap">
                            <thead>
                                <tr>
                                    <th width="20">S.No</th>
                                    <th>Product Name</th>
                                    <th>Expiry Date</th>
                                    <th>Batch Code</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                            @if(isset($vendor_claim_order_details))
                            @foreach($vendor_claim_order_details AS $key => $record)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{ $record->product_name }}</td>
                                    <td>{{ \CommonHelpers::date_format_custom($record->expiry_date) }}</td>
                                    <td>{{ $record->batch_code }}</td>
                                    <td>{{ $record->item_price }}</td>
                                    <td>{{ $record->qty }}</td>
                                    <td>{{ $record->subtotal }}</td>
                                    {{-- <td><input type="checkbox" value={{ $record->hashid }}></td> --}}
                                </tr>
                            @endforeach 
                            @endif
                            </tbody>
                        </table>
                        <a href="{{ route('admin.vendor_claim_order_details.export',[$order_id]) }}" class="btn btn-primary float-right mb-1">Export</a>
                    </div> 
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
    });
</script>
@endsection

