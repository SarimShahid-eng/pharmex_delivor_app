@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories') }}">Categories</a></li>
                    <li class="breadcrumb-item active">{{ (@$is_edit) ? 'Edit' : 'Add' }}</li>
                </ol>
            </div>
            <h4 class="page-title">{{ (@$is_edit) ? 'Edit' : 'Add' }} Category</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card custom_filters">
            <div class="card-header bg-dark text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#filters_div" role="button" aria-controls="filters_div" class=""><i class="mdi mdi-plus"></i></a>
                </div>
                <h4 class="card-title mb-0 text-white">Category <small>(Import CSV File)</small></h4>
            </div>
            <div id="filters_div" class="card-body collapse show ">
                <form action="{{ route('admin.categories.import') }}" method="post" class="row align-items-end justify-content-center mb-2 ajaxForm" enctype='multipart/form-data'>
                    @csrf
                    <div class="col-12">
                        <div class="row">
                            <div class="col-sm-4 text-center offset-sm-3">
                                <label for="order_no" class="sr-onlys mr-1">Import CSV</label>
                                <input type="file" name="csv_file" accept=".csv,.CSV" id="csv" class="form-control-file" required="">
                                <a href="{{ asset('uploads/csvsheet/category.csv') }}" class="d-block mt-3">Category Format</a>
                            </div>

                            <div class="col-sm-2 ">
                                <button type="submit" class="btn btn-primary waves-effect waves-light" style="margin-top: 24px;">Import</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
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

                
            </form>
        </div>
    </div>
</div>
@endsection