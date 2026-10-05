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
                    <li class="breadcrumb-item active">Groups</li>
                </ol>
            </div>
            <h4 class="page-title">{{ $title }} </h4>
        </div>
    </div>
</div>
@if(CommonHelpers::rights('group_add'))
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">{{ (@$is_edit) ? 'Edit' : 'Add New' }} Group</h4>
            <p class="text-muted font-14 m-b-20">
                Here you can {{ (@$is_edit) ? 'update' : 'create' }} Group.
            </p>

            <form action="{{ route('admin.group.save') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="region_name">Group Name<span class="text-danger">*</span></label>
                            <input type="text" name="group_name" parsley-trigger="change" required placeholder="Enter Group Name" class="form-control" id="branch_name" value="{{ isset($group_data) ? $group_data->group_name : '' }}">

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="area_name">Venders</label><span class="text-danger">*</span>
                            <select class="form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose ..." required value="{{ isset($region) ? $region->area_name : '' }}" name='venders[]' >
                            @foreach($customers_data as $customers_data)
                            <optgroup label="{{$customers_data->company_name}}">
                                <option <?php if(isset($group_data)){ foreach (@$group_data->companies as $value) {
                                    if ($value->id == $customers_data->id) {
                                        echo "selected";
                                    }
                                }} ?> value="{{ $customers_data->hashid }}">All ({{$customers_data->company_name}})</option>
                                @foreach($customers_data->parent as $parent_data)
                                <option <?php if(isset($group_data)){ foreach (@$group_data->companies as $value) {
                                    if ($value->id == $parent_data->id) {
                                        echo "selected";
                                    }
                                }} ?> value="{{ $parent_data->hashid }}">{{ $parent_data->company_name }}</option>
                                @endforeach
                            </optgroup>    
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>    





                <div class="form-group mb-3 text-right">
                    <input type="hidden" value="{{ @$group_data->hashid }}" name="group_id" />
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
</div>
@endif
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Groups</h4>
             </div>
            <p class="sub-header">Following is the list of all the Groups.</p>
           <div class="table-responsive">
            <table class="table dt_table table-bordered table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="30">S.No</th>
                        <th>Group Name</th>
                        <th>Venders</th>
                        <th>Action</th>
                    </tr>
                </thead>

            </table>
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

<script>
$(document).ready(function () {

    $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 0,
        lengthMenu: [10, 20, 50, 100, 200, 500],
        ajax: "{{ route('admin.group.list') }}",
        columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'group_name', name: 'group_name'},
            {data: 'venders', name: 'venders'},
            {data: 'action',name: 'action',orderable: false}
        ]
    });
});
</script>
@endsection

