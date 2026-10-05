@extends('layouts.frontend')
@section('content')
<!-- Slider Start -->
<div class="homepage-slides-wrapper">
    <div class="homepage-slides text-center">
        <!-- Slider item2 start-->
        <div class="single-slide-item slide-bg-2">
            <div class="item-table">
                <div class="item-tablecell">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h2>INTERNATIONAL SHIPPING <br> SPECIALISTS</h2>
                                <p>Your Package is yours and you Entrust it in Our Care. Whether you require any Kind of <br> Shipping Services or Container. We are here to Assist you and Ship your <br>Belongings Safe and on Time</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Slider item2 end-->
    </div>
</div>
<!-- Slider End -->

<!-- Why Choose start -->
<div class="wshipping-content-block">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-4 wow fadeInLeft">
                <img src="{{ asset('frontend_assets') }}/images/why-choose-us.jpg" alt="" />
            </div>
            <div class="col-12 col-lg-4">
                <div class="why-choose-us-content">
                    <h3 class="heading3-border text-uppercase"><span>Welcome</span> To International Express Courier</h3>
                    <p>International Express Courier is a global shipping and away relocation company based in Quetta, Pakistan since 2000. IEC offers fast, reliable and secure international and nationwide package deliveries. We have our own offices and warehouse locations all over the world. We committed to giving hassle-free, cost-effective and reliable forward logistics services with no last-minute surprises and no hidden charges. You will be comfortable knowing that International Express Courier will take everything.</p>
                    <a href="#" title="" class="readmore-btn">Read more <i class="fa fa-angle-right"></i></a>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="why-choose-us wow fadeInRight">
                    <div class="why-choose-us-icon">
                        <i class="fa fa-thumbs-o-up"></i>
                        Fast & Safe Delivery
                    </div>
                    <div class="why-choose-us-icon">
                        <i class="fa fa-smile-o"></i>
                        AFFORDABLE PRICE
                    </div>
                    <div class="why-choose-us-icon">
                        <i class="fa fa-handshake-o"></i>
                        EASY COMMUNICATION
                    </div>
                    <div class="why-choose-us-icon">
                        <i class="fa fa-question"></i>
                        CUSTOMER SERVICE & SUPPORT
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- why choose End -->

<div class="wshipping-content-block provided-block text-center">
    <div class="item-table">
        <div class="item-tablecell">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <img src="{{ asset('frontend_assets') }}/images/track_iconbtm.png" class="mb-4">
                        <h1 class="text-uppercase mb-4">TRACK YOUR SHIPMENT HERE</h1>
                        <div class="col-md-8 offset-md-2">
                            <div class="banner-form-two">
                                <form method="get" action="{{ route('front.track') }}">
                                    <div class="row clearfix">
                                        <div class="form-group col-lg-9 col-md-6 col-sm-12">
                                            <input type="text" name="order_id" placeholder="ENTER ORDER ID" required>
                                        </div>

                                        <div class="form-group col-lg-3 col-md-12 col-sm-12">
                                            <button type="submit" class="banner-searchbtn">Track</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Service process start -->
<div class="wshipping-content-block service-process">
    <div class="container wow fadeInUp">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3">
                <div class="section-title">
                    <h2><span>Our Service</span> Process</h2>
                </div>
            </div>
        </div>
        <div class="process-row">
            <div class="process-step">
                <div class="process-icon">
                    <span>1</span>
                    <img src="{{ asset('frontend_assets') }}/images/process-icon1.png" alt="" />
                </div>
                <p>Select Freight</p>
            </div>
            <div class="process-step">
                <div class="process-icon">
                    <span>2</span>
                    <img src="{{ asset('frontend_assets') }}/images/process-icon2.png" alt="" />
                </div>
                <p>Create Invoice</p>
            </div>
            <div class="process-step">
                <div class="process-icon">
                    <span>3</span>
                    <img src="{{ asset('frontend_assets') }}/images/process-icon3.png" alt="" />
                </div>
                <p>Secure Payment</p>
            </div>
            <div class="process-step">
                <div class="process-icon">
                    <span>4</span>
                    <img src="{{ asset('frontend_assets') }}/images/process-icon4.png" alt="" />
                </div>
                <p>Fast & Safe Delivery</p>
            </div>
        </div>
    </div>
</div>
<!-- service process start -->

<!-- News &amp; Testimonials Start -->
<div class="wshipping-content-block news-testimonial-block">
    <div class="container wow fadeInUp">
        <div class="row">
            <!-- Testimonial start -->
            <div class="col-12 col-lg-12 home-testimonial">
                <div class="section-title">
                    <h2><span>Our</span> Testimolials</h2>
                    <div class="quote_icon">
                        <img src="{{ asset('frontend_assets') }}/images/quote.png">
                    </div>
                </div>
                <div class="testimonial">
                    <div class="testimonial-item">
                        <!-- <div class="row"> -->
                        <div class="col-12">
                            <div class="testimonial-title">
                                <div class="row">
                                    <div class="col-md-3 col-sm-4">
                                        <img src="{{ asset('frontend_assets') }}/images/team3.jpg" class="rounded-circle">
                                    </div>
                                    <div class="col-md-9 col-sm-8 pl-0">
                                        <h4>Raymon Myers</h4>
                                        <p>CEO</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="testimonial-content">
                                <p>IEC has been excellent the staff was trained and polite and walked me through step by step. This is my first international move and they made it easy and all costs associated were laid out clear as day. They were the only company who had all prices clearly labeled on their estimate I would clearly use them again</p>
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>
                    <div class="testimonial-item">
                        <!-- <div class="row"> -->
                        <div class="col-12">
                            <div class="testimonial-title">
                                <div class="row">
                                    <div class="col-md-3 col-sm-4">
                                        <img src="{{ asset('frontend_assets') }}/images/team2.jpg" class="rounded-circle">
                                    </div>
                                    <div class="col-md-9 col-sm-8 pl-0">
                                        <h4>Ashley Foster</h4>
                                        <p>CEO</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="testimonial-content">
                                <p>This is my second move and quality of service and very competitive prices are why I came back. The delivery team was friendly, very active, and expert. I'll be using International Express Courier service again.</p>
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>

                    <div class="testimonial-item">
                        <!-- <div class="row"> -->
                        <div class="col-12">
                            <div class="testimonial-title">
                                <div class="row">
                                    <div class="col-md-3 col-sm-4">
                                        <img src="{{ asset('frontend_assets') }}/images/team4.jpg" class="rounded-circle">
                                    </div>
                                    <div class="col-md-9 col-sm-8 pl-0">
                                        <h4>Matti Sears</h4>
                                        <p>CEO</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="testimonial-content">
                                <p>Great company. Great team. Great communication. Moved from the Pakistan to the USA and the USA service surpassed our expectations. thanks so much for being expert, helpful and communicative!</p>
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>

                </div>
            </div>
            <!-- Testimonial end -->
        </div>
    </div>
</div>
<!-- News & Testimonials End -->
@endsection