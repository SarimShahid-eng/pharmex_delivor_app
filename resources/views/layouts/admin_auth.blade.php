<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') - Pharmex</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link href="{{ asset('frontend_assets') }}/images/favicon.png" sizes="128x128" rel="shortcut icon" />
    
    <!-- App css -->
    <link href="{{ asset('admin_assets') }}/css/bundled.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets') }}/css/dianujStyles.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg authentication-bg-pattern">

    <div class="account-pages mt-5 mb-2">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <a href="{{ route('admin.home') }}">
                                    <span>
                                        <img src="{{ asset('admin_assets') }}/images/color_header_logo.png" alt="{{ config('app.name') }}" width="180">
                                        {{-- <h1></h1> --}}
                                    </span>
                                </a>
                                <p class="text-muted mb-4 mt-3">@yield('page-heading')</p>
                            </div>
                            @yield('content')
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    @yield("after-page")
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->


    <footer class="footer footer-alt">
        {{ date('Y') }} &copy; All rights reserved by {{config('app.name')}}. Design &amp; Developed By <a href="https://gexton.com" target="_blank">GEXTON INC</a>.
    </footer>

    <script src="{{ asset('admin_assets') }}/js/bundled.min.js"></script>

    @yield('page-scripts')

    <script src="{{ asset('admin_assets') }}/js/app.min.js"></script>

</body>

</html>