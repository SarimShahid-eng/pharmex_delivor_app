<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>{{ $title ?? 'Dashboard' }} - Pharmex Distribution System</title>
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
            .bootstrap-select .inner{
                overflow-y: auto !important;
            }
            .bell{
                position: relative;
                z-index: 101;
            }
        </style>
    </head>

    <body class="left-side-menu-dark">

        <div id="preloader" class="preloader">
            <div id="status">
                <div class="spinner">Loading...</div>
            </div>
        </div>

        <!-- Begin page -->
        <div id="wrapper">
            <!-- Topbar Start -->
            <div class="navbar-custom">
            
                <ul class="list-unstyled topnav-menu float-right mb-0">                   
                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="{{ check_file(auth()->user()->image, 'user') }}" alt="{{ auth()->user()->full_name }}" class="rounded-circle fit-image">
                            <span class="pro-user-name ml-1">
                                {{ auth()->user()->full_name }} <i class="mdi mdi-chevron-down"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome {{ auth()->user()->full_name }}!</h6>
                            </div>

                            <!-- item-->
                            <!-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-user"></i>
                                <span>My Account</span>
                            </a> -->

                            <!-- item-->
                            <!-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-settings"></i>
                                <span>Settings</span>
                            </a> -->

                            <!-- item-->
                            <a href="{{ route('admin.change_password') }}" class="dropdown-item notify-item">
                                <i class="fe-star"></i>
                                <span>Change Password</span>
                            </a>


                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <a href="{{ route('logout') }}" onclick="logout(event)" class="dropdown-item notify-item">
                                <i class="fe-log-out"></i>
                                <span>Logout</span>
                            </a>

                        </div>
                    </li>

                    <!-- <li class="dropdown notification-list">
                        <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                            <i class="fe-settings noti-icon"></i>
                        </a>
                    </li> -->


                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="{{ route('admin.home') }}" class="logo text-center">
                        <span class="logo-lg">
                            <img src="{{ asset('admin_assets') }}/images/color_header_logo.png" alt="{{ config('app.name') }}" width="180">
                            <!--<img src="{{ asset('admin_assets') }}/images/web_logo_light.png" alt="" height="40">-->
                            <!-- <span class="logo-lg-text-light">UBold</span> -->
                        </span>
                        <span class="logo-sm">
                            <h1 style="color:#FFF;">CP</h1>
                           <!--<img src="{{ asset('admin_assets') }}/images/web_logo_light_sm.png" alt="" height="40">-->
                        </span>
                    </a>
                </div>

                <!-- <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="fe-menu"></i>
                        </button>
                    </li>
                </ul> -->
            </div>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <div class="topnav">
                <div class="container-fluid">
                    <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                        <div class="collapse navbar-collapse active" id="topnav-menu-content">
                            <ul class="navbar-nav active">
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="{{ route('admin.home') }}" id="topnav-dashboard" role="button">
                                        <i class="fe-airplay mr-1"></i> Dashboards 
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="{{ route('admin.regions') }}" id="topnav-dashboard" role="button">
                                        <i class="fas fa-location-arrow"></i> Rout 
                                    </a> 
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="{{ route('admin.town') }}" id="topnav-dashboard" role="button">
                                        <i class="fas fa-location-arrow"></i> Town 
                                    </a> 
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-users"></i> Companies
                                         <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a class="nav-link" href="{{ route('admin.company') }}" id="topnav-dashboard" role="button" style="color: #212323!important;">
                                        View Companies 
                                        </a>
                                        <a class="nav-link" href="{{ route('admin.company.add') }}" style="color: #212323!important;">
                                        Add Companies 
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-users"></i> Customers
                                         <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a class="nav-link" href="{{ route('admin.customers') }}" id="topnav-dashboard" role="button" style="color: #212323!important;">
                                        View Customers 
                                        </a>
                                        <a class="nav-link" href="{{ route('admin.customers.add') }}" style="color: #212323!important;">
                                        Add Customer 
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-archive"></i> Products
                                         <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a class="nav-link" href="{{ route('admin.products') }}" id="topnav-dashboard" role="button" style="color: #212323!important;">
                                        View Products 
                                        </a>
                                        <a class="nav-link" href="{{ route('admin.products.add') }}" style="color: #212323!important;">
                                        Add Product 
                                        </a>
                                    </div>
                                </li>
                               <!--  <li class="nav-item dropdown">
                                    <a class="nav-link" href="{{ route('admin.customers') }}" id="topnav-dashboard" role="button">
                                        <i class="fas fa-users"></i> Customers 
                                    </a> 
                                </li> -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="{{ route('admin.groups') }}" id="topnav-dashboard" role="button">
                                        <i class="fas fa-users"></i> Groups 
                                    </a> 
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-users"></i> Employees
                                         <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a class="nav-link" href="{{ route('admin.employee') }}" id="topnav-dashboard" role="button" style="color: #212323!important;">
                                        View Employees 
                                        </a>
                                        <a class="nav-link" href="{{ route('admin.employee.add') }}" style="color: #212323!important;">
                                        Add Employee 
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="{{ route('admin.task') }}" id="topnav-dashboard" role="button">
                                        <i class="fas fa-tasks"></i> TASK 
                                    </a> 
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="{{ route('admin.orders') }}" id="topnav-dashboard" role="button">
                                        <i class="fas fa-shopping-cart"></i> Orders 
                                    </a> 
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="{{ route('admin.orders.return') }}" id="topnav-dashboard" role="button">
                                        <i class="fas fa-shopping-cart"></i> Orders Return
                                    </a> 
                                </li>
                            </ul> <!-- end navbar-->
                        </div> <!-- end .collapsed-->
                    </nav>
                </div> <!-- end container-fluid -->
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
                <!-- Footer Start -->
                <footer class="footer" style="left:0px;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                {{ date('Y') }} &copy; All rights reserved by Pharmex Distribution System. Design &amp; Developed By <a href="https://gexton.com" target="_blank">GEXTON INC</a>.
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->
            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <script src="{{ asset('admin_assets') }}/js/bundled.min.js"></script>
        <script>
                                function pageloader(status) {
                                    if (status == 'hide') {

                                        $('.preloader').hide();
                                        $('#status').hide();
                                        return;
                                    }

                                    $('.preloader').show();
                                    $('#status').show();
                                    return;
                                }
        </script>

        @yield('page-scripts')

        <script src="{{ asset('admin_assets') }}/js/app.min.js"></script>
        <script src="{{ asset('admin_assets') }}/js/custom.js"></script>

    </body>

</html>