@extends('layouts.admin')
@section('content')
<style>
      .lows{
         background-color: #eab7b7;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Items</li>
                </ol>
            </div>
            <h4 class="page-title">All Items</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Items</h4>
            </div>
            <a href="{{ route('admin.export_item') }}" target="_blank"><button class="dt-button buttons-copy buttons-html5" tabindex="0"type="button"><span>Export Data</span></button></a><br><br>
            <div class="table-responsive">
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="30">S.No</th>
                        <th>Item <br>Code</th>
                        <th>Item<br> Name</th>
                        <th>Category</th>
                        <th>Unit<br> Size</th>
                        <th>CTN <br>Size</th>
                        <th>CTN <br>Price</th>
                        <th>Stock <br>Level</th>
                        @if(auth()->user()->is_admin)
                        <th>status</th>
                        <th>Action</th>
                        @endif
                    </tr>
                </thead>
                
            </table>
            </div>
            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myLargeModalLabel">Details</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                    <div class="modal-body">
                                        <table class="table dt_table table-bordered w-100 nowrap responsive">
                                           <thead>
                                               <tr>
                                                   <th>Item Code</th>
                                                   <th>Par Level</th>
                                                   <th>Taxable</th>
                                                   <th>Groups</th>
                                                   <th>Unit Name</th>
                                                   <th>Unit price</th>
                                                   <th>Notes</th>
                                               </tr>
                                           </thead> 
                                           <tbody id="item_data"></tbody>
                                        </table>            
                                    </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')

@include('admin.partials.ajaxDatatable', ['load_swtichery' => true])
<script>
$(document).ready(function () {

  var table =  $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.items.list') }}",
        columns: [
            {data: 'rownum', name: 'rownum'},
            {data: 'items_code', name: 'items_code'},
            {data: 'items_name', name: 'items_name'},
            {data: 'category_name', name: 'category_name'},
            {data: 'unit_size', name: 'unit_size'},
            {data: 'ctn_size', name: 'ctn_size'},
            {data: 'ctn_cost', name: 'ctn_cost'},
            {data: 'stock_levels', name: 'stock_levels'},
           @if(auth()->user()->is_admin)
                {data: 'status', name: 'status'},
            {
                data: 'action',
                name: 'action',
                orderable: false
            }
            @endif
        ],
         createdRow: function ( row, data, index ) {
          
          if(data.stock_levels == "Low"){
          $('td', row).addClass('lows');
      }
    }
    });
    
     table.on('draw', function () {
        $('[data-toggle="switchery"]').each(function (a, e) {
            new Switchery($(this)[0], $(this).data())
        });
    });
    $(document).on('click' , '.details_btn' , function(){
        id = $(this).data("id"); 
        $.ajax({
                url: "{{ route('admin.items.model_data') }}",
                method: 'post',
                data: {'id':id, "_token": "{{ csrf_token() }}",},
                success: function (result) {
                    var items = JSON.parse(result); 
                        let _html = 'tr';
                        $(items.items).each(function(index, items){ 
                _html += `<tr><td> ${items.item_code} </td><td> ${items.par_level} </td><td> ${items.taxable} </td><td> ${items.groups} </td><td> ${items.unit_name } </td><td> ${items.unit_cost} </td><td> ${items.description} </td></tr>`;
                });
                        $("#item_data").html(_html);
                },
                error: function (msg) {

                },
                
        }); 
    });



});
</script>
@endsection