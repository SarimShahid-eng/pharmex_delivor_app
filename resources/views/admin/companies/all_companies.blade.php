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
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Companies</li>
                </ol>
            </div>
            <h4 class="page-title">All Companies</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Companies</h4>
            @if(CommonHelpers::rights('company_add'))     
                <a class="d-inline-block btn btn-primary waves-effect waves-light" href="{{ route('admin.company.add') }}">Add New Company</a>
            @endif    
            </div>
            <p class="sub-header">Following is the list of all the Companies.</p>
            <!-- <div class="table-responsive">
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="20">S.No</th>
                        <th>Company Name</th>
                        <th>Company Number</th>
                        <th>Division</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            </div> -->
        <div class="row">    
        @foreach($company as $company_data)    
            <div class="col-md-3 col-xl-3">
                <div class="widget-rounded-circle card-box border">
                    <div class="row border-bottom pb-2">
                        <div class="col-10">
                            <div class="media">
                                <img src="{{ asset('admin_assets') }}/images/icons/companies_icon.png" class="img-fluid mr-2"/>
                                <div class="media-body">
                                    <h4 class="mt-2 mb-0">{{ $company_data->company_name }}</h4>
                                    <!--<h5 class="mt-0" style="color: #6b6b6b;font-size: 14px;">Employee</h5>-->
                                </div>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="text-right mt-2">
                                <a type="button" name="edit" href="{{ route('admin.company.edit',$company_data->hashid) }}" class="btn btn-outline-primary btn-rounded waves-effect waves-light model_btn bg-white shadow-sm" style="padding: 5px 8px;"><i class="fas fa-pencil-alt text-primary"></i></a>
                                {{-- <img src="{{ asset('admin_assets') }}/images/icons/info.png" class="img-fluid"/> --}}
                            </div>
                        </div>
                    </div> <!-- end row-->
                    <div class="row mt-3">
                        <div class="col-6 text-center border-right">
                            <h4 class="mt-0 mb-1"></h4>
                            <p class="mb-0">Company Sale</p>
                        </div>
                        <div class="col-6 text-center">
                            <h4 class="mt-0 mb-1" style="color: #ff531e"></h4>
                            <h4 class="mb-0 mb-1" style="color: #ff531e">Rs.  {{ $company_data->amt != null ? $company_data->amt : '0'}}</h4>
                        </div>
                    </div>
                </div> <!-- end widget-rounded-circle-->
            </div>
        @endforeach
        </div>
        {{ $company->links() }}    
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

<script>
$(document).ready(function () {

    $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 0,
        lengthMenu: [10, 20, 50, 100, 200, 500],
        ajax: "{{ route('admin.company.list') }}",
        columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'company_name', name: 'company_name'},
            {data: 'division', name: 'division'},
            {data: 'ph_num', name: 'ph_num'},
            {data: 'action',name: 'action',orderable: false}
        ]
    });
});

$('#filter_search').on('submit',function(e) {
    e.preventDefault();
    var table = $('#laravel_datatable').DataTable();
    var region_id = $('#region_id').val(); 
    if ($.fn.DataTable.isDataTable( '#laravel_datatable' ) ) { 
          table.destroy(); 
        $('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 0,
            lengthMenu: [10, 20, 50, 100, 200, 500],
            ajax: {
                url:"{{ route('admin.filter_customers.list') }}",
                type: "post",
                data: {
                      'region_id':region_id,
                      "_token": "{{ csrf_token() }}",
                },
            },
            columns: [
                {data: 'rownum', name: 'rownum'},
                {data: 'customer_name', name: 'customer_name'},
                {data: 'org_name', name: 'org_name'},
                {data: 'ph_num', name: 'ph_num'},
                {data: 'regions.region_name', name: 'regions.region_name'},
                {data: 'item_reminder', name: 'item_reminder'},
                {data: 'location', name: 'location'},
                {data: 'value',name: 'value'},
                {data: 'action',name: 'action',orderable: false}
            ]
        });  
    }
})
</script>
@endsection

