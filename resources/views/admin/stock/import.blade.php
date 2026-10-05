@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Import Stock</li>
                </ol>
            </div>
            <h4 class="page-title">{{ @$title }}</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0 text-center"> Stock Import</h4>
            <p class="text-muted font-14 m-b-20 text-center">
                Stock (Import CSV File)
            </p>
            <form action="{{ route('admin.imports.stock_csv') }}" method="post" class="row align-items-end justify-content-center mb-1 ajaxForm" enctype='multipart/form-data'>
                @csrf

               
                <div class="form-group mb-1 ">
                        <label for="order_no" class="sr-onlys mr-1">Import CSV</label>
                        <input type="file" name="csv_file" accept=".csv,.CSV" id="csv" class="" required="">
                </div>
                <div class="form-group mb-1 text-right">

                    <button type="submit" class="btn btn-primary waves-effect waves-light">Import</button>

                </div>
                {{-- <a href="{{ asset('uploads/csvsheet/towns.csv') }}" class="d-block ">Town Format</a> --}}

                 {{-- <span><b>Note</b>: Use only for additional new entries, or use edit to change the names of existing names</span> --}}

            </form>

            <div class="text-center">
                <a href="{{ asset('uploads/csvsheet/stocks.csv') }}" download class="">Stock Format</a>
                <p>
                    <strong>Note:</strong> Use only for import 
                    stocks,
                </p>
            </div>

        </div>
    </div>
</div>
@endsection