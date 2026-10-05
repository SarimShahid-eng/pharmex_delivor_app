@extends('layouts.admin')
@section('content')
<style>
    #datatables_buttons_info h2{
        color: black !important;
    }
    #datatables_buttons_info{
        color: black !important;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </div>
            <h4 class="page-title">All Categories</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card-box" style="height: 232px;">
            <h4 class="header-title m-t-0">{{ (@$is_edit) ? 'Edit' : 'Add New' }} Category</h4>
            <p class="text-muted font-14 m-b-20">
                Here you can {{ (@$is_edit) ? 'update' : 'create' }} Categories.
            </p>

            <form action="{{ route('admin.categories.save') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <div class="form-group mb-3">
                    <label for="category_name">Category Name<span class="text-danger">*</span></label>
                    <input type="text" name="category_name" parsley-trigger="change" required placeholder="Enter Category Name" class="form-control" id="branch_name" value="{{ isset($category) ? $category->category_name : '' }}">
                </div>



                <div class="form-group mb-3 text-right">
                    <input type="hidden" value="{{ @$category->hashid }}" name="category_id" />
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        Submit
                    </button>
                    <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                        Cancel
                    </button>
                </div>

            </form>
        </div>
    </div>
@if(auth()->user()->is_admin)
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="header-title m-t-0"> Category Import</h4>
            <p class="text-muted font-14 m-b-20">
                Category (Import CSV File)
            </p>
            <form action="{{ route('admin.categories.import') }}" method="post" class="row align-items-end justify-content-center mb-2 ajaxForm" enctype='multipart/form-data'>
                @csrf

               
                <div class="form-group mb-3 ">
                        <label for="order_no" class="sr-onlys mr-1">Import CSV</label>
                        <input type="file" name="csv_file" accept=".csv,.CSV" id="csv" class="form-control" required="">
                       
                </div>
                    <div class="form-group mb-3 text-right">

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Import</button>

                    </div>
                 <a href="{{ asset('uploads/csvsheet/category.csv') }}" class="d-block ">Category Format</a>

                 <span><b>Note</b>: Use only for additional new entries, or use edit to change the names of existing names</span>

            </form>
        </div>
    </div>
@endif
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Categories</h4>
             </div>
            <p class="sub-header">Following is the list of all the Categories.</p>
            <a href="{{ route('admin.export_categories') }}" target="_blank"><button class="dt-button buttons-copy buttons-html5" tabindex="0"type="button"><span>Export Data</span></button></a><br><br>
           <div class="table-responsive">
            <table class="table dt_table table-bordered table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="30">S.No</th>
                        <th>Categories Name</th>

                        <th>Action</th>
                    </tr>
                </thead>

            </table>
        </div>
              </div>
           <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myLargeModalLabel">Send Email</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                    <div class="modal-body">
                                        <form id="send_email" method="post">
                                        @csrf  
                                        <h5 style="color:green" id="success"></h5> 
                                        <h5 style="color:red" id="failed"></h5>   
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="firstname">Email<span class="text-danger">*</span></label>
                                                    <input type="email" id="email" name="email" parsley-trigger="change" placeholder="Enter Email" class="form-control" value="" required="">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit" style="    margin-top: 27px;">
                                                        SEND
                                                    </button>
                                                </div>
                                            </div>
                                        </div>       
                                        </form>    
                                    </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
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
        ajax: "{{ route('admin.categories.list') }}",
        columns: [
             {data: 'rownum', name: 'rownum'},
            {data: 'category_name', name: 'category_name'},
            {data: 'action',name: 'action',orderable: false}
        ]
    });
});
</script>
@endsection

