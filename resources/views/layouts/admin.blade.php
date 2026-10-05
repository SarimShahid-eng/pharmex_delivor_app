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
            .select2-container .select2-selection--single .select2-selection__rendered{line-height: 1.9;}
            .bootstrap-select .inner{overflow-y: auto !important;}
            .topnav .navbar-nav .nav-link i{display:inline-block !important; font-size: 20px !important;}
            .topnav .navbar-nav .nav-link{padding-right: 5px !important; padding-left: 5px !important; border-bottom: 4px solid transparent;margin: 0 2px !important;}
            .topnav{height: auto !important; background: #000 !important;}
            .topnav .dropdown:hover > .nav-link,
            .topnav .navbar-nav .nav-link:focus, .topnav .navbar-nav .nav-link:hover{color: #ff4105 !important; border-bottom-color: #ff4105;}
            .dropdown-menu a{border-bottom-color: transparent !important;}
            .micon{background-repeat: no-repeat;padding: 8px;background-size: cover;}
            .dashboard{background-image: url("{{ asset('admin_assets') }}/images/icons/dashboard.png");}
            .topnav .navbar-nav .nav-link:focus .micon.dashboard,
            .topnav .navbar-nav .nav-link:hover .micon.dashboard{background-image: url("{{ asset('admin_assets') }}/images/icons/dashboard_hover.png");}

            .rout{background-image: url("{{ asset('admin_assets') }}/images/icons/route.png");}
            .topnav .navbar-nav .nav-link:focus .micon.rout,
            .topnav .navbar-nav .nav-link:hover .micon.rout{background-image: url("{{ asset('admin_assets') }}/images/icons/route_hover.png");}


            .town{background-image: url("{{ asset('admin_assets') }}/images/icons/town.png");}
            .topnav .navbar-nav .nav-link:focus .micon.town,
            .topnav .navbar-nav .nav-link:hover .micon.town{background-image: url("{{ asset('admin_assets') }}/images/icons/town_hover.png");}

            .companies{background-image: url("{{ asset('admin_assets') }}/images/icons/companies.png");}
            .topnav .navbar-nav .nav-link:focus .micon.companies,
            .topnav .navbar-nav .nav-link:hover .micon.companies{background-image: url("{{ asset('admin_assets') }}/images/icons/companies_hover.png");}

            .customers{background-image: url("{{ asset('admin_assets') }}/images/icons/customers.png");}
            .topnav .navbar-nav .nav-link:focus .micon.customers,
            .topnav .navbar-nav .nav-link:hover .micon.customers {background-image: url("{{ asset('admin_assets') }}/images/icons/customers_hover.png");}

            .products{background-image: url("{{ asset('admin_assets') }}/images/icons/products.png");}
            .topnav .navbar-nav .nav-link:focus .micon.products,
            .topnav .navbar-nav .nav-link:hover .micon.products{background-image: url("{{ asset('admin_assets') }}/images/icons/products_hover.png");}

            .groups{background-image: url("{{ asset('admin_assets') }}/images/icons/groups.png");}
            .topnav .navbar-nav .nav-link:focus .micon.groups,
            .topnav .navbar-nav .nav-link:hover .micon.groups{background-image: url("{{ asset('admin_assets') }}/images/icons/groups_hover.png");}

            .employees{background-image: url("{{ asset('admin_assets') }}/images/icons/employees.png");}
            .topnav .navbar-nav .nav-link:focus .micon.employees,
            .topnav .navbar-nav .nav-link:hover .micon.employees{background-image: url("{{ asset('admin_assets') }}/images/icons/employees_hover.png");}

            .task{background-image: url("{{ asset('admin_assets') }}/images/icons/task.png");}
            .topnav .navbar-nav .nav-link:focus .micon.task,
            .topnav .navbar-nav .nav-link:hover .micon.task{background-image: url("{{ asset('admin_assets') }}/images/icons/task_hover.png");}

            .orders{background-image: url("{{ asset('admin_assets') }}/images/icons/orders.png");}
            .topnav .navbar-nav .nav-link:focus .micon.orders,
            .topnav .navbar-nav .nav-link:hover .micon.orders{background-image: url("{{ asset('admin_assets') }}/images/icons/orders_hover.png");}

            .orderreturn{background-image: url("{{ asset('admin_assets') }}/images/icons/ordersreturn.png");}
            .topnav .navbar-nav .nav-link:focus .micon.orderreturn,
            .topnav .navbar-nav .nav-link:hover .micon.orderreturn{background-image: url("{{ asset('admin_assets') }}/images/icons/ordersreturn_hover.png");}

            .fa-bell{
                margin-top: 30px;
                margin-right: 20px;
                position: relative;
                font-size: 20px!important;
            }
            .red{
                height: 8px;
                width: 8px;
                background-color: #ff4105;
                border-radius: 20px;
                top: 0;
                right: -5px;
                position: absolute;
            }
            .b-color{
                color: #000;
            }
            .b-color:hover{
                color: #000;
            }
            .topnav .navbar-nav .nav-link{
                font-size: 13px;
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
                    <li><a href="{{ route('admin.notification.all_notifications') }}" class="b-color"><div class="fas fa-bell">
                        <div class="red"></div>
                        </div></a>
                    </li>
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
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.home') }}" id="topnav-dashboard" role="button">
                                        <i class="micon dashboard mr-1"></i> Dashboards
                                    </a>
                                </li>
                            @if(auth()->user()->user_role == 'company')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.company.products') }}" id="topnav-dashboard" role="button">
                                        <i class="micon products mr-1"></i> Products 
                                    </a> 
                                </li>
                            @endif    
                             @if(CommonHelpers::rights('routs_view'))    
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.regions') }}" id="topnav-dashboard" role="button">
                                        <i class="micon rout mr-1"></i> Rout 
                                    </a> 
                                </li>
                            @endif
                            @if(CommonHelpers::rights('town_view'))    
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.town') }}" id="topnav-dashboard" role="button">
                                        <i class="micon town mr-1"></i> Town 
                                    </a> 
                                </li>
                            @endif    
                            @if(CommonHelpers::rights('company_view')) 
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="micon companies mr-1"></i> Companies
                                         <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a class="nav-link" href="{{ route('admin.company') }}" id="topnav-dashboard" role="button" style="color: #212323!important;">
                                        View Companies 
                                        </a>
                            @if(CommonHelpers::rights('company_add'))     
                                        <a class="nav-link" href="{{ route('admin.company.add') }}" style="color: #212323!important;">
                                        Add Companies 
                                        </a>
                            @endif            
                                    </div>
                                </li>
                            @endif   
                            @if(CommonHelpers::rights('customer_view')) 
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="micon customers mr-1"></i> Customers
                                         <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a class="nav-link" href="{{ route('admin.customers') }}" id="topnav-dashboard" role="button" style="color: #212323!important;">
                                        View Customers 
                                        </a>
                                    @if(CommonHelpers::rights('customer_add'))    
                                        <a class="nav-link" href="{{ route('admin.customers.add') }}" style="color: #212323!important;">
                                        Add Customer 
                                        </a>
                                    @endif    
                                    </div>
                                </li>
                            @endif    
                            @if(CommonHelpers::rights('product_view')) 
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="micon products mr-1"></i> Products
                                         <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a class="nav-link" href="{{ route('admin.products') }}" id="topnav-dashboard" role="button" style="color: #212323!important;">
                                        View Products 
                                        </a>
                            @if(CommonHelpers::rights('product_add'))     
                                        <a class="nav-link" href="{{ route('admin.products.add') }}" style="color: #212323!important;">
                                        Add Product 
                                        </a>
                                    @endif    
                                    </div>
                                </li>
                            @endif 
                            @if(CommonHelpers::rights('group_view'))   
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.groups') }}" id="topnav-dashboard" role="button">
                                        <i class="micon groups mr-1"></i> Groups 
                                    </a> 
                                </li>
                            @endif    
                            @if(CommonHelpers::rights('employees_view'))   
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="micon employees mr-1"></i> Employees
                                         <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a class="nav-link" href="{{ route('admin.employee') }}" id="topnav-dashboard" role="button" style="color: #212323!important;">
                                        View Employees 
                                        </a>
                                    @if(CommonHelpers::rights('employees_add'))    
                                        <a class="nav-link" href="{{ route('admin.employee.add') }}" style="color: #212323!important;">
                                        Add Employee 
                                        </a>
                                    @endif    
                                    </div>
                                </li>
                            @endif  
                            @if(CommonHelpers::rights('task_add'))   
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="micon employees mr-1"></i> TASK
                                         <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a class="nav-link" href="{{ route('admin.task') }}" id="topnav-dashboard" role="button" style="color: #212323!important;">
                                        Add TASK 
                                        </a>
                                   
                                        <a class="nav-link" href="{{ route('admin.task_workflow.index') }}" style="color: #212323!important;">
                                        Manage Task workflow  
                                        </a>
                             
                                    </div>
                                </li>
                            @endif  
                            <!--@if(CommonHelpers::rights('task_add'))  -->
                            <!--    <li class="nav-item">-->
                            <!--        <a class="nav-link" href="{{ route('admin.task') }}" id="topnav-dashboard" role="button">-->
                            <!--            <i class="micon task mr-1"></i> TASK -->
                            <!--        </a> -->
                            <!--    </li>-->
                            <!--@endif    -->
                            @if(CommonHelpers::rights('orders_view'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.orders') }}" id="topnav-dashboard" role="button">
                                        <i class="micon orders mr-1"></i> Orders 
                                    </a> 
                                </li>
                            @endif
                            @if(CommonHelpers::rights('orders_return_view'))
                                {{-- <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.pending_orders') }}" id="topnav-dashboard" role="button">
                                        <i class="micon orderreturn mr-1"></i> Orders Return
                                    </a> 
                                </li> --}}
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="micon orderreturn mr-1"></i> Expiry Return
                                         <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a class="nav-link" href="{{ route('admin.pending_orders') }}" id="topnav-dashboard" role="button" style="color: #212323!important;">
                                        Pending Orders
                                        </a>
                                        <a class="nav-link" href="{{ route('admin.approved_orders') }}" style="color: #212323!important;">
                                        Approved Orders
                                        </a>
                                        <a class="nav-link" href="{{ route('admin.lifted_orders') }}" style="color: #212323!important;">
                                        Lifted Orders
                                        </a>
                                        <a class="nav-link" href="{{ route('admin.rejected_orders') }}" style="color: #212323!important;">
                                            Rejected Orders
                                        </a>

                                        <a class="nav-link" href="{{ route('admin.vendor_claims') }}" style="color: #212323!important;">
                                            Vendor Claim
                                        </a>
                                    </div>
                                </li>
                            @endif      
                            @if(auth()->user()->is_admin)    
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="micon groups mr-1"></i> Users
                                         <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a class="nav-link" href="{{ route('admin.users') }}" id="topnav-dashboard" role="button" style="color: #212323!important;">
                                        View Users 
                                        </a>
                                        <a class="nav-link" href="{{ route('admin.users.add') }}" style="color: #212323!important;">
                                        Add User 
                                        </a>
                                    </div>
                                </li>
                            @endif 
                            
                            @if(auth()->user()->is_admin)    
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="micon groups mr-1"></i> Stock
                                         <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                                        <a class="nav-link" href="{{ route('admin.imports.show') }}" style="color: #212323!important;">
                                        Stock 
                                        </a>
                                        <a class="nav-link" href="{{ route('admin.uploads.bonus.pdf') }}" id="topnav-dashboard" role="button" style="color: #212323!important;">
                                            Bonus 
                                            </a>
                                    </div>
                                </li>
                            @endif 
                             
                            {{-- @if(CommonHelpers::rights('orders_view'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.imports.show') }}" id="topnav-dashboard" role="button">
                                        <i class="micon orders mr-1"></i> Stock 
                                    </a> 
                                </li>
                            @endif  --}}
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