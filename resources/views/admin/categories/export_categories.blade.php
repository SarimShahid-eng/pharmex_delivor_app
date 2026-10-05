<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>{{ $title ?? 'Dashboard' }} - Cuisine Corp</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        @yield('before-css')
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('admin_assets') }}/images/favicon.png">
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,900&display=swap" rel="stylesheet">


        <!-- Plugins css -->
        <link href="{{ asset('admin_assets') }}/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_assets') }}/css/bundled.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_assets') }}/css/dianujStyles.css" rel="stylesheet" type="text/css" />
        <style>
            .select2-container .select2-selection--single .select2-selection__rendered {
                line-height: 1.9;
            }
            #datatables_buttons_info h2{
                color: black !important;
            }
            #datatables_buttons_info{
                color: black !important;
            }
        </style>
    </head>

    <body class="left-side-menu-dark">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="header-title">{{ $title }}</h1>
                    </div> 
                    <button class="details_btn" data-toggle="modal" data-target=".bs-example-modal-lg">Send Email</button><br><br>
                    <div class="responsive" id="table_div">
                    <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th width="30">S.No</th>
                                <th>Categories Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cat_data as $k => $cat_data)
                            <tr>    
                                <td>{{ $cat_data->id }}</td>
                                <td>{{ $cat_data->category_name }}</td>
                            </tr>
                             @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myLargeModalLabel">Send Email</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                    <div class="modal-body">
                                        <form id="send_email" method="post">
                                         @csrf   
                                        <h5 style="color:green" id="success"></h5> 
                                        <h5 style="color:red" id="failed"></h5>  
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="firstname">Email<span class="text-danger">*</span></label>
                                                    <input type="email" id="email" name="email" parsley-trigger="change" placeholder="Enter Email" class="form-control" value="" required="">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit" style="    margin-top: 27px;">
                                                        SEND
                                                    </button>
                                                </div>
                                            </div>
                                        </div>       
                                        </form>    
                                    </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                </div>
                </div>
            </div>
        </div>
        
        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <script src="{{ asset('admin_assets') }}/js/bundled.min.js"></script>

<link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js
"></script>
@include('admin.partials.datatable')
<script>
$(document).ready(function () {
   
   $('#send_email').on('submit',function(e) {
       e.preventDefault();
       var email = $('#email').val(); 
       $.ajax({
            url: "{{ route('admin.categories.categories_send_email') }}",
            type: "post",
            data: {'email':email,
                  "_token": "{{ csrf_token() }}",
            },
            success: function(res) {
                if (res) {
                    $('#success').append('Email send successfully');
                }else{ $('#failed').append('Email send failed'); }
            }
        })      
    });
    
});
</script>

        <script src="{{ asset('admin_assets') }}/js/app.min.js"></script>
        <script src="{{ asset('admin_assets') }}/js/custom.js"></script>

    </body>

</html>