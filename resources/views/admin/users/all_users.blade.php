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
                    <li class="breadcrumb-item active">users</li>
                </ol>
            </div>
            <h4 class="page-title">All Users</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Users</h4>
                <a class="d-inline-block btn btn-primary waves-effect waves-light" href="{{ route('admin.users.add') }}">Add New User</a>
            </div>
            <p class="sub-header">Following is the list of all the users.</p>
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="20">S.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th width="40">User Role</th>
                        <th>Rights</th>
                        <th>Added On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $k => $user)
                    <tr>
                        <td>
                            <p class="m-0 text-center">{{ $k + 1 }}</p>
                        </td>
                        <td>{{ $user->fullname }}</td>
                        <td><small>{{ $user->email }}</small></td>
                        <td>
                            <p class="m-0 text-center">
                                <span class="badge badge-{{ user_type_colors($user->user_role) }}">{{ ucfirst($user->user_role) }}</span>
                            </p>
                        </td>
                        <td>
                            <p class="m-0 text-center">
                                @if(@json_decode($user->rights)->stock_in)
                                <small class="badge badge-light">Stock In</small>
                                @endif
                                @if(@json_decode($user->rights)->stock_out)
                                <small class="badge badge-light">Stock Out</small>
                                @endif

                                @if(@json_decode($user->rights)->stock_in && @json_decode($user->rights)->stock_out && @json_decode($user->rights)->stock_view)
                                <br>
                                @endif
                                
                                @if(@json_decode($user->rights)->stock_view)
                                <small class="badge badge-light">Stock View</small>
                                @endif
                                @if(@json_decode($user->rights)->items_View)
                                <small class="badge badge-light">Item View</small>
                                @endif

                            </p>
                        </td>
                        <td>
                            <p class="m-0"><small>{{ get_date($user->created_on) }}</small></p>
                            <small class="text-muted">By: {{ $user->added_by->fullname ?? '-' }}</small>
                        </td>
                        <td>
                            <p class="m-0 text-center">
                                <input type="checkbox" class="nopopup" onchange="ajaxRequest(this)" data-url="{{ route('admin.users.change_status', $user->hashid) }}" {{ $user->is_active ? 'checked' : ''}} data-toggle="switchery" data-size="small" data-color="#1bb99a" />
                            </p>
                        </td>
                        <td>
                            @can('update', $user)
                            <a href="{{ route('admin.users.edit', $user->hashid) }}" class="btn btn-outline-primary btn-rounded waves-effect waves-light">
                                <i class="icon-pencil"></i>
                            </a>

                            <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.users.delete', $user->hashid) }}" class="btn btn-outline-danger btn-rounded waves-effect waves-light">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            @endcan
                        </td>
                    </tr>
                
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@include('admin.partials.datatable', ['load_swtichery' => true])
@endsection