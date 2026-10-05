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
                    <li class="breadcrumb-item active">Locations</li>
                </ol>
            </div>
            <h4 class="page-title">All Locations</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card-box" style="height: 232px;">
            <h4 class="header-title m-t-0">{{ (@$is_edit) ? 'Edit' : 'Add New' }} Location</h4>
            <p class="text-muted font-14 m-b-20">
                Here you can {{ (@$is_edit) ? 'update' : 'create' }} Locations.
            </p>

            <form action="{{ route('admin.location.save') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate>
                @csrf


                <div class="row"> 
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="total_amount">Location Name<span class="text-danger">*</span></label>
                            <input type="text" name="location_name" parsley-trigger="change" required placeholder="Enter Location Name" class="form-control" id="branch_name" value="{{ isset($branch) ? $branch->location_name : '' }}"> </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="Site">Site</label>
                            <select id="site" name="site" parsley-trigger="change" class="form-control" required="">
                                @foreach($site as $val)
                                <option value="{{ $val->site_name }}" {{ isset($branch) && $branch->site == $val->site_name ? 'selected=""' : '' }}>{{ $val->site_name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                </div>

                <div class="form-group mb-3 text-right">
                    <input type="hidden" value="{{ @$branch->hashid }}" name="location_id" />
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
            <h4 class="header-title m-t-0"> Location Import</h4>
            <p class="text-muted font-14 m-b-20">
                Location (Import CSV File) 
            </p>
            <form action="{{ route('admin.location.import') }}" method="post" class="row align-items-end justify-content-center mb-2 ajaxForm" enctype='multipart/form-data'>
                @csrf


                <div class="form-group mb-3 ">
                    <label for="order_no" class="sr-onlys mr-1">Import CSV</label>
                    <input type="file" name="csv_file" accept=".csv,.CSV" id="csv" class="form-control" required="">

                </div>
                <div class="form-group mb-3 text-right">

                    <button type="submit" class="btn btn-primary waves-effect waves-light">Import</button>

                </div>
                <a href="{{ asset('uploads/csvsheet/location.csv') }}" class="d-block ">Location Format</a>
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
                <h4 class="header-title">Locations</h4>
            </div>
            <p class="sub-header">Following is the list of all the Locations.</p>
            <a href="{{ route('admin.export_locations') }}" target="_blank"><button class="dt-button buttons-copy buttons-html5" tabindex="0"type="button"><span>Export Data</span></button></a><br><br>
            <div class="table-responsive">
                <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                    <thead>
                        <tr>
                            <th width="30">S.No</th>
                            <th>Locations Name</th>
                            <th>Site Name</th>
                            <th>Status</th>
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
@include('admin.partials.ajaxDatatable', ['load_swtichery' => true])

<script>
    $(document).ready(function () {

        var table = $('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 0,
            lengthMenu: [10, 20, 50, 100, 200, 500],
            ajax: "{{ route('admin.location.list') }}",
            columns: [
                {data: 'rownum', name: 'rownum'},
                {data: 'location_name', name: 'location_name'},
                {data: 'site', name: 'site'},
                {data: 'status', name: 'status'},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]

        });

        table.on('draw', function () {
            $('[data-toggle="switchery"]').each(function (a, e) {
                new Switchery($(this)[0], $(this).data())
            });
        });


    });
</script>   
@endsection

