@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Policies</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ @$title }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <a href="{{ route('admin.policies.create') }}" class="btn btn-primary float-right mb-2">Add Policy</a>
                <h4 class="header-title m-t-0">All Policies</h4>

                <table id="policies_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Policies</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($policies as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->title }}</td>
                                <td>
                                    <ul class="pl-3 mb-0">
                                        @foreach ($item->policies as $p)
                                            <li>{{ $p }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input status-switch"
                                            id="status_{{ $item->id }}" data-id="{{ $item->id }}"
                                            {{ $item->is_active ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status_{{ $item->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <a type="button" name="edit" href="{{ route('admin.policies.edit', $item->id) }}"
                                        class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i
                                            class="fas fa-pencil-alt"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="button" name="edit" onclick="ajaxRequest(this)"
                                        data-url="{{ route('admin.policies.delete', $item->id) }}"
                                        class="btn btn-outline-danger btn-rounded waves-effect waves-light"><i
                                            class="fas fa-trash-alt"></i></button>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#policies_table').DataTable();

        $(document).on('change', '.status-switch', function() {
            var $this = $(this);
            var status = $this.is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('admin.policies.status') }}",
                type: "POST",
                data: {
                    id: $this.data('id'),
                    status: status
                },
                success: function() {
                    if (status === 1) {
                        // uncheck every other switch, including rows on other pages
                        $(table.rows().nodes()).find('.status-switch').not($this).prop('checked',
                            false);
                    }
                },
                error: function() {
                    $this.prop('checked', !status); // revert on failure
                    alert('Something went wrong, please try again.');
                }
            });
        });
    </script>
@endsection
