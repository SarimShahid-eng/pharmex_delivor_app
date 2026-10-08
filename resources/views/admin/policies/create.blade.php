@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.policies.index') }}">Policy</a></li>
                        <li class="breadcrumb-item active">{{ @$is_edit ? 'Edit' : 'Add New' }} Policy</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ @$is_edit ? 'Edit' : 'New' }} Policy</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title m-t-0">{{ @$is_edit ? 'Edit' : 'Add New' }} Policy</h4> <br>
                <form action="{{ route('admin.policies.save') }}" class="ajaxForm" method="post" novalidate>
                    @csrf

                    <div class="form-group mb-3">
                        <label for="title">Title<span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Enter Title"
                            value="{{ @$policy->title ?? '' }}" required>
                    </div>

                    <label>Policies<span class="text-danger">*</span></label>
                    <div id="policy_list">
                        @php $rows = !empty(@$policy->policies) ? $policy->policies : ['']; @endphp
                        @foreach ($rows as $row)
                            <div class="input-group mb-2 policy-row">
                                <input type="text" name="policies[]" class="form-control" placeholder="Enter Policy"
                                    value="{{ $row }}" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-policy" title="Remove"><i
                                            class="mdi mdi-close"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="add_policy" class="btn btn-success btn-sm mb-3">
                        <i class="mdi mdi-plus"></i> Add Policy
                    </button>

                    <div class="form-group mb-3 text-right">
                        <input type="hidden" name="update_id" value="{{ @$policy->id }}" />
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            {{ @$is_edit ? 'Update' : 'Submit' }}
                        </button>
                        <a href="{{ route('admin.policies.index') }}"
                            class="btn btn-secondary waves-effect m-l-5">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script type="text/javascript">
        function policyRow() {
            return `
        <div class="input-group mb-2 policy-row">
            <input type="text" name="policies[]" class="form-control" placeholder="Enter Policy" required>
            <div class="input-group-append">
                <button type="button" class="btn btn-danger remove-policy" title="Remove"><i class="mdi mdi-close"></i></button>
            </div>
        </div>`;
        }

        $('#add_policy').on('click', function() {
            $('#policy_list').append(policyRow());
        });

        // remove row (always keep at least one)
        $(document).on('click', '.remove-policy', function() {
            if ($('.policy-row').length > 1) {
                $(this).closest('.policy-row').remove();
            } else {
                $(this).closest('.policy-row').find('input').val('');
            }
        });
    </script>
@endsection
