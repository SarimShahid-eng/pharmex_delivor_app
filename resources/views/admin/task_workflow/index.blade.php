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
                    <li class="breadcrumb-item active">Task Workflow</li>
                </ol>
            </div>

            <h4 class="page-title">Add Task Workflow</h4>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Task Workflow List</h4>
                <a href="{{route('admin.task_workflow.create')}}" class="btn btn-primary waves-effect waves-light">Task Workflow</a>
            </div>
            <p class="sub-header">Following is the list of all Task Workflows.</p>
            </a>
            <br><br>

            <div class="table-responsive">
                <table class="table dt_table table-bordered w-100 nowrap" id="task_workflow_datatable">
                    <thead>
                        <tr>
                            <th width="30">S.No</th>
                            <th>Title</th>
                            <th>Employee</th>
                            <th>Month Name</th>
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

<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {

    $('#task_workflow_datatable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        lengthMenu: [10, 20, 50, 100, 200, 500],
        ajax: "{{ route('admin.task_workflow.task_workflow_list') }}",
        columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'title', name: 'title'},
            {data: 'employee', name: 'employee'},
            {data: 'month_name', name: 'month_name'},
            {data: 'action', name: 'action', orderable: false}
        ]
    });
});
</script>

@endsection
