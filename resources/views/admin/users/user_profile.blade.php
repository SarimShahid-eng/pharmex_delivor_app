@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Your Profile</li>
                </ol>
            </div>
            <h4 class="page-title">Your Profile</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">Your Profile Details</h4>
            <p class="text-muted font-14 m-b-20">
                Here you can change your name and other details if applicable.
            </p>

            <form action="{{ route('admin.update_profile') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate >
                @csrf
                <div class="row my-3 align-items-center border-bottom pb-3">
                    <div class="col-sm-6">
                        <p class="help-block mb-1">Current Profile Image</p>
                        <img src="{{ check_file(auth()->user()->image, 'user') }}" alt="image" class="rounded-circle fit-image" width="120">
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="profile_pic">Upload New Profile Pic</label>
                            <input type="file" name="profile_pic" accept=".gif, .jpg, .png" id="profile_pic" class="form-control-file">
                        </div>
                        <span class="help-block d-block">Image size must be smaller than <strong>500KB.</strong> </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="firstname">Firstname<span class="text-danger">*</span></label>
                            <input type="text" name="firstname" parsley-trigger="change" required placeholder="Enter Firstname" class="form-control" id="firstname" value="{{ auth()->user()->firstname }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lastname">Last Name<span class="text-danger">*</span></label>
                            <input type="text" name="lastname" parsley-trigger="change" required placeholder="Enter Last Name" class="form-control" id="lastname" value="{{ auth()->user()->lastname }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <p class="form-control" value="">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                </div>

                <div class="form-group mb-3 text-right">
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

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">Change Password</h4>
            <p class="text-muted font-14 m-b-20">
                Here you can change your password provided you know your current password.
            </p>

            <form action="{{ route('admin.update_password') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate >
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="current_password">Current Password<span class="text-danger">*</span></label>
                            <input type="password" name="current_password" parsley-trigger="change" required placeholder="Enter Current Password" class="form-control" id="current_password">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="new_password">New Password<span class="text-danger">*</span></label>
                            <input type="password" name="new_password" parsley-trigger="change" required placeholder="Enter New Password" class="form-control" id="new_password">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password<span class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" parsley-trigger="change" required placeholder="Enter Confirm Password" class="form-control" id="confirm_password" data-parsley-equalto="#new_password" data-parsley-equalto-message="This field must match with new password">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3 text-right">
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

@if(auth()->user()->is_admin)
{{-- <div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">Change Palltes Quantity Limit</h4>
            <p class="text-muted font-14 m-b-20">
                Here you can change palltes quantity provided you know your current palltes limit.
            </p>

            <form action="{{ route('admin.pallet_quantity') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate >
                @csrf

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="confirm_password">Palltes Quantity Limit<span class="text-danger">*</span></label>
                            <input type="number" name="pallet_qty" parsley-trigger="change" required placeholder="Enter Limits" class="form-control" id="pallet_qty" value="{{ @$pallet_qty->qty }}">
                        </div>
                    </div>
                </div>
                <input type="hidden" value="{{ @$pallet_qty->hashid }}" name="pallet_qty_id" />
                <div class="form-group mb-3 text-right">
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
</div> --}}

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">Change Sites Name</h4>

            <form action="{{ route('admin.siteUpdate') }}" class="" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <div class="row">
                    {{-- @foreach($site as $val) --}}

                    <div class="col-6">
                        <div class="form-group">
                            <label for="confirm_password">Site Name<span class="text-danger">*</span></label>
                            <input type="text" name="site_name" parsley-trigger="change" required placeholder="Enter Site Name" class="form-control" id="site_name" value="{{ @$site->site_name }}">
                        </div>
                    </div>

                    {{-- <input type="hidden" value="{{ @$site->hashid }}" name="site_id" /> --}}
                    {{-- @endforeach --}}
                </div>
                <div class="col-12 text-right">
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

@endsection