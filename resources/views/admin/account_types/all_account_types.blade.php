@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Account Types</li>
                </ol>
            </div>
            <h4 class="page-title">All Account Types</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Account Types</h4>
                <a class="d-inline-block btn btn-primary waves-effect waves-light" href="javascript:void(0)" onclick="account_type_modal(false, null)">Add New Account Type</a>
            </div>
            <p class="sub-header">Following is the list of all the account types.</p>
            <table class="table dt_table table-bordered w-100 nowrap">
                <thead>
                    <tr>
                        <th width="30">S.No</th>
                        <th>Name</th>
                        <th>Branch</th>
                        <th>Added On</th>
                        <th>Added By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($account_types as $k => $account_type)
                    <tr>
                        <td>{{ $k + 1 }}</td>
                        <td>{{ $account_type->name }}</td>
                        <td>{{ $account_type->branch->branch_name }}</td>
                        <td>{{ get_date($account_type->created_at) }}</td>
                        <td>{{ $account_type->added_by->fullname }}</td>
                        <td>
                            <button type="button" onclick="account_type_modal(true, '{{ $account_type->hashid }}', '{{ $account_type->name }}')" class="btn btn-warning btn-xs waves-effect waves-light">
                                <span class="btn-label"><i class="icon-pencil"></i></span>Edit
                            </button>
                            <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.account_types.delete', $account_type->hashid) }}" class="btn btn-danger btn-xs waves-effect waves-light">
                                <span class="btn-label"><i class="icon-trash"></i></span>Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-center" id="accountTypeAdd" tabindex="-1" role="dialog" aria-labelledby="accountTypeAddLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="accountTypeAddLabel">Order Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form class="ajaxForm" id="add_form" action="{{ route('admin.account_types.save') }}" method="POST">
                    @csrf
                    @if(auth()->user()->is_admin)
                    <div class="form-group" id="branch_id_div">
                        <label for="branch_id">Select Branch<span class="text-danger">*</span></label>
                        <select id="branch_id" name="branch_id" parsley-trigger="change" class="form-control" required>
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->hashid }}">{{ $branch->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="account_name">Account Type Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="account_name" parsley-trigger="change" name="name" placeholder="Enter Account Type Name" required />
                    </div>
                    <div class="form-group mb-3 text-right">
                        <input type="hidden" id="account_type_id" name="account_type_id" style="display:none"/>
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
<script>
    function account_type_modal(is_edit, account_type_id, account_name) {
        $("#add_form").trigger('reset');
        $("#account_type_id").val(null).hide();
        $("#branch_id_div").show();
        if(is_edit){
            $("#branch_id_div").hide();
            $("#account_type_id").val(account_type_id).show();
            $("#account_name").val(account_name);
        }
        $("#accountTypeAdd").modal('show');
    }
</script>
@endsection