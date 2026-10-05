@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Stocks</li>
                </ol>
            </div>
            <h4 class="page-title">{{ @$title }}</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box float-left w-100 mt-2">
            <a href="{{ route('admin.imports.stock') }}" class="btn btn-primary float-right">Stock Import</a>
            <h4 class="header-title m-t-0  float-left"> Seach Products</h4>
            {{-- <p class="text-muted font-14 m-b-20 text-center">
                Town (Import CSV File)
            </p> --}}
            <div class="col-12 float-left w-100">
            <form action="{{ route('admin.imports.search') }}" method="get" class="align-items-end mb-2" enctype='multipart/form-data'>
                @csrf
                <div class="form-group mb-3">
                        <label for="order_no" class="sr-onlys mr-1">Products</label>
                        <select class="form-control" name="product_id" id="product_id">
                            <option value="">Select Product</option>
                            @foreach($products AS $product)
                                <option value="{{ $product->product_code }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="file" name="csv_file" accept=".csv,.CSV" id="csv" class="form-control" required=""> --}}
                       
                </div>
                    <div class="form-group mb-3 text-right">

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Search</button>

                    </div>
                 {{-- <a href="{{ asset('uploads/csvsheet/towns.csv') }}" class="d-block ">Town Format</a> --}}

                 {{-- <span><b>Note</b>: Use only for additional new entries, or use edit to change the names of existing names</span> --}}

            </form>
        </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            {{-- <h4>Products</h4> --}}
            <table id="stocks" class="table table-bordered">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>Product code</th>
                    <th>Product Title</th>
                    {{-- <th>Product Title</th> --}}
                    <th>Batch Code</th>
                    <th>Expiry Date</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @if(@$search == TRUE)
                        @foreach($data AS $key=>$item) 
                            <tr>
                                <td>{{ $key+1 }}</td>
                                {{-- <td>{{ $product_name[intVal($item->product_id)]->product_name }}</td> --}}
                                <td>{{ intVal($item->product_id) }}</td>
                                <td>{{ $item->product_title }}</td>
                                <td>{{ $item->batch_no }}</td>
                                <td class="expiry_date" id="{{ $item->hashid }}">{{ date('d-m-Y',strtotime($item->expiry_date)) }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ $item->rate }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--MODAL-->
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Stock</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.imports.stock_fields.update') }}" method="POST" class="ajaxForm" id="edit_form">
              @csrf
                
                
            </form>
          </div>
      </div>
</div>
@endsection
@section('page-scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#product_id').select2();
    });
</script>
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready( function () {
        $('#stocks').DataTable();
    });
    $('.expiry_date').click(function(){
        var id = $(this).attr('id');
        $.ajax({
            url : "{{ route('admin.imports.stock_fields.edit') }}",
            type:"get",
            data:{ id:id },
            success:function(resp){
                $('#edit_form').html(resp.html);
                $('#edit_modal').modal('show');
            }
        });
    });
</script>
@endsection