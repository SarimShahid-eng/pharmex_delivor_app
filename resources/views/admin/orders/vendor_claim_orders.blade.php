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
                    <li class="breadcrumb-item active">Vendor Claim orders</li>
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
                <h4 class="header-title">Vendor Claim Orders</h4>
            </div>
            <p class="sub-header">Following is the list of vendor claim orders.</p>
            <form action="{{ route('admin.vendor_claim_orders.view') }}" method="get">
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
            </form>
            <br><br>
              
                    <div class="table_main">
                        <table class="table dt_table table-bordered w-100 nowrap">
                            <thead>
                                <tr>
                                    <th width="20">S.No</th>
                                    <th>Company Name</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(isset($data))
                            @forelse($data AS $key => $record)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{$record->childCompany->company_name}}</td>
                                    <td>{{ $record->total_amount }}</td>
                                    <td>{{ $record->status }}</td>
                                    {{-- <td>{{ \CommonHelpers::date_format_custom($record->created_at) }}</td> --}}
                                    <td>{{ date('d-m-Y',strtotime($record->created_at)) }}</td>
                                    <td>
                                    <a  name="edit" href="{{route('admin.vendor_claim_orders.detail',[$record->hashid])}}" class="btn btn-outline-primary btn-rounded waves-effect waves-light " ><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" align="center">No Record Found</td>
                                </tr>    
                            @endforelse
                            @endif
                            </tbody>
                        </table>
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

