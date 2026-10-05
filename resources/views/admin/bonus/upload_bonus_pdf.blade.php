@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Bonus</li>
                </ol>
            </div>
            <h4 class="page-title">{{ @$title }}</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0"> Upload Bonus</h4>
            <p class="text-muted font-14 m-b-20">
                Upload Bonus (PDF)
            </p>
            <form action="{{ route('admin.stores.bonus.pdf') }}" method="post" class="mb-2 ajaxForm" enctype='multipart/form-data'>
                @csrf

                <div class="row align-items-end justify-content-center">
                    <div class="col-md-4">
                        <label for="order_no" class="sr-onlys mr-1">Upload PDF</label>
                        <input type="file" name="pdf_file" accept=".pdf,.PDF" id="csv" class="form-control" required="">
                    </div>

                    <div class="col-md-4">
                        <label for="file_name" class="sr-onlys mr-1">Enter File Name</label>
                        <input type="text" name="file_name"  id="file_name" class="form-control" required="">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Import</button>
                    </div>

                </div>

                 {{-- <a href="{{ asset('uploads/csvsheet/towns.csv') }}" class="d-block ">Town Format</a> --}}

                 {{-- <span><b>Note</b>: Use only for additional new entries, or use edit to change the names of existing names</span> --}}

            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <table class="table table-bordered" id="pdf_table">
                <thead>
                    <th>#</th>
                    <th>File Name</th>
                    <th>Upload Date</th>
                    <th>Download</th>
                </thead>
                <tbody>
                    @foreach($pdfs AS $key=>$pdf)
                        @if(file_exists(public_path($pdf->file_path)))
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $pdf->file_name }}</td>
                                <td>{{ date('d-M-Y',strtotime($pdf->created_at)) }}</td>
                                <td><a href="{{ asset($pdf->file_path) }}" class="btn btn-primary" download>Download</a></td>
                                
                            </tr>
                        @endif    
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
{{-- @section('page-scripts')
<script>
    $(document).ready( function () {
    $('#pdf_table').DataTable();
} );
</script>
@endsection --}}