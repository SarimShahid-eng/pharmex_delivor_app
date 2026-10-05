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
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">{{ (@$is_edit) ? 'Edit' : 'Add New' }} Target</h4>
            <p class="text-muted font-14 m-b-20">
                Here you can {{ (@$is_edit) ? 'update' : 'create' }} Target.
            </p>

            <form action="{{ route('admin.target.save') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <input type="hidden" name="employee_id" value="{{ @$user_id }}">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="region_name">Month<span class="text-danger">*</span></label>
                            <select class="form-control" name="month" required="">
                                <option value="">select</option>
                            <?php for($y=0; $y<=11; $y++){ $a = date('m-Y', mktime(0, 0, 0, date('m')+$y, 1, date('Y'))); ?>
                                   <option value="{{  date('d-m-Y', mktime(0, 0, 0, date('m')+$y, 1, date('Y'))) }}" {{ @$a == @$task_data->month ? 'selected' : ''}}>{{  date('F Y', mktime(0, 0, 0, date('m')+$y, 1, date('Y'))) }}</option>

                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="area_name">Target</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" name="target" value="{{ @$task_data->target }}" required="">
                        </div>
                    </div>
                </div>    

                <div class="form-group mb-3 text-right">
                    <input type="hidden" value="{{ @$task_data->hashid }}" name="task_id" />
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

