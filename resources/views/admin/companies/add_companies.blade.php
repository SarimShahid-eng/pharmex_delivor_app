@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">{{ $title }}</a></li>
                    <li class="breadcrumb-item active">{{@$is_edit ? 'Edit' : 'Add New'}} {{ $title }}</li>
                </ol>
            </div>
            <h4 class="page-title">{{@$is_edit ? 'Edit' : 'New'}} {{ $title }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">{{@$is_edit ? 'Edit' : 'Add New'}} {{ $title }}</h4>
            <form action="{{ route('admin.company.save') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Company Name<span class="text-danger">*</span></label>
                            <input type="text" id="firstname" name="company_name" parsley-trigger="change" placeholder="Enter Company Name" class="form-control" value="{{ @$customer->company_name ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Company Number</label>
                            <input type="text" id="firstname" name="ph_num" parsley-trigger="change" placeholder="Enter Company Number" class="form-control" value="{{ @$customer->ph_num ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Division</label>
                            <input type="text" id="firstname" name="division" parsley-trigger="change" placeholder="Enter Division" class="form-control" value="{{ @$customer->division ?? '' }}">
                        </div>
                    </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                            <label for="parent">Parent</label>
                            <select id="parent" name="parent_id" class="form-control">
                                <option value="">select...</option>
                                @foreach($company_data as $company_data)
                                <option  value="{{ $company_data->hashid }}" {{ isset($company_data) && @$company_data->id == @$customer->parent_id ? 'selected' : ''}}>{{ $company_data->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                   <div class="form-group mb-3 text-right">
                           <input type="hidden" value="{{ @$customer->hashid }}" name="customer_id" />
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


       