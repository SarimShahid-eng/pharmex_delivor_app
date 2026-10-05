<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="International Express Courier Cargo" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Title -->
    <title>{{ $title ?? '' }} International Express Courier Cargo</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('frontend_assets') }}/images/favicon.png">

    <!-- bundled CSS -->
    <link rel="stylesheet" href="{{ asset('frontend_assets') }}/css/bundled.min.css">
    <!-- jQuery -->
    <script src="{{ asset('frontend_assets') }}/js/jquery-2.1.3.min.js"></script>
    <style>
        .track-result-id {
            border-radius: 0;
        }

        ul.track-progress li {
            width: 25%;
        }

        ul.track-progress li:before,
        ul.track-progress li:after {
            background-color: #555;
        }

        ul.track-progress li.icon-transit:before {
            content: "\f0d1";
            transform: scaleX(-1);
        }

        ul.track-progress li.icon-cubes:before {
            content: "\f1b3";
        }

        ul.track-progress li.icon-check-circle:before {
            content: "\f058";
        }
    </style>
</head>

<body>
    <!-- Main Wrapper Start -->
    <div class="main-wrapper">
        <header>
            <!-- Header area start -->
            <div class="header-area">
                <div class="container">
                    <div class="row">
                        <!-- Site logo Start -->
                        <div class="col-md-4">
                            <div class="logo">
                                <a href="index.php">
                                    <img src="{{ asset('frontend_assets') }}/images/logo.png" />
                                </a>
                            </div>
                        </div>
                        <div class="col-md-7 ml-auto">
                            <div class="row mt-5">
                                <div class="col-md-4 border-right">
                                    <div class="media">
                                        <i class="fa fa-mobile"></i>
                                        <div class="media-body">
                                            <h5 class="mt-0">{{ $_settings->contact->mobile ?? '-'}}</h5>
                                            <p>Call Us Toll Free</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 border-right">
                                    <div class="media">
                                        <i class="fa fa-map-marker"></i>
                                        <div class="media-body">
                                            <h5 class="mt-0">{{ $_settings->contact->address_city ?? '-'}}</h5>
                                            <p>{{ $_settings->contact->address_state ?? '-'}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="media">
                                        <i class="fa fa-clock-o"></i>
                                        <div class="media-body">
                                            <h5 class="mt-0">{{ $_settings->business_hours->sat_thu ?? '-'}}</h5>
                                            <p>Thursday - Saturday</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 res_trackbtn">
                                    <a href="track.php"><img src="{{ asset('frontend_assets') }}/images/track_icon.png"> Track</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="nav-bg">
                    <div class="container">
                        <div class="mobile-menu-wrapper"></div>
                        <!-- Main menu start -->
                        <nav class="mainmenu">
                            <ul id="navigation" class="float-left">
                                <li class="{{ isset($menu) && $menu == 'home' ? 'nav-active' : '' }}"><a href="{{ route('front.home') }}">Home</a></li>
                                <li class="{{ isset($menu) && $menu == 'services' ? 'nav-active' : '' }}"><a href="{{ route('front.services') }}">Services</a></li>
                                <li class="{{ isset($menu) && $menu == 'track' ? 'nav-active' : '' }}"><a href="{{ route('front.track') }}">Tracking</a></li>
                                <li class="{{ isset($menu) && $menu == 'about' ? 'nav-active' : '' }}"><a href="{{ route('front.about') }}">About Us</a></li>
                                <li class="{{ isset($menu) && $menu == 'contact' ? 'nav-active' : '' }}"><a href="{{ route('front.contact') }}">Contact Us</a></li>
                            </ul>

                            <ul class="float-right track_rightlink">
                                <div class="dropdown header-search-bar">
                                    <form action="{{ route('front.track') }}" method="get" class="">
                                        <span class="" data-toggle="dropdown" aria-expanded="false"><img src="{{ asset('frontend_assets') }}/images/track_icon.png"> Track</span>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="order_id" placeholder="Enter Order ID">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <button type="submit" class="banner-searchbtn"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </ul>
                        </nav>
                        <!-- Main menu end -->
                    </div>
                </div>
                <!-- Header area End -->
            </div>
        </header>
        <!-- Preloader start -->
        <div class="wshipping-site-preloader-wrapper">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
        </div>
        <!-- Preloader End -->

        @yield('content')

        <!-- Footer start -->
        <footer class="site-footer">
            <!-- Footer Top start -->
            <div class="footer-top-area wow fadeInUp">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="footer-wiz">
                                <h3 class="footer-logo"><img src="{{ asset('frontend_assets') }}/images/footer-logo.png" alt="footer logo" /></h3>
                                <p>{{ $_settings->footer_about ?? '-'}}</p>
                                <ul class="footer-contact">
                                    <li><i class="fa fa-phone"></i> {{ $_settings->contact->phone ?? '-'}}</li>
                                    <li><i class="fa fa-mobile"></i> {{ $_settings->contact->mobile ?? '-'}}</li>
                                    <li><i class="fa fa-envelope"></i> {{ $_settings->contact->email ?? '-'}}</li>
                                </ul>
                            </div>
                            <div class="top-social bottom-social">
                                @if(isset($_settings->social_links))
                                @foreach($_settings->social_links as $key => $value)
                                @if(!empty($value))
                                <a href="{{$value}}"><i class="fa fa-{{$key}}"></i></a>
                                @endif
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="footer-wiz footer-menu">
                                <h3 class="footer-wiz-title">Quick Links</h3>
                                <ul>
                                    <li><a href="{{ route('front.about') }}">About Us</a></li>
                                    <li><a href="{{ route('front.services') }}">Our Services</a></li>
                                    <li><a href="{{ route('front.contact') }}">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="footer-wiz footer-menu">
                                <h3 class="footer-wiz-title">Usefull Links</h3>
                                <ul>
                                    <li><a href="{{ route('front.track') }}">Tracking</a></li>
                                    <li><a href="{{ route('front.terms') }}">Terms &amp; Conditions</a></li>
                                    <li><a href="{{ route('front.privacy') }}">Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="footer-wiz">
                                <h3 class="footer-wiz-title">Opening Hours</h3>
                                <ul class="open-hours">
                                    <li><span>Thr - Sat:</span> <span class="text-right">{{ $_settings->business_hours->sat_thu ?? '-'}}</span></li>
                                    <li><span>Fri:</span> <span class="text-right">{{ $_settings->business_hours->friday ?? '-'}}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- footer top end -->

            <!-- copyright start -->
            <div class="footer-bottom-area">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">Copyright © {{ date('Y') }} <span>International Express Courier Cargo</span>.</div>
                    </div>
                </div>
            </div>
            <!-- copyright end -->
        </footer>
        <!-- Footer end -->
    </div>
    <!-- Main Wrapper end -->

    <!-- Start scroll top -->
    <div class="scrollup"><i class="fa fa-angle-up"></i></div>
    <!-- End scroll top -->

    <!-- Tether JS -->
    <script src="{{ asset('frontend_assets') }}/js/js.js"></script>
    @yield('page-scripts')
</body>

</html>