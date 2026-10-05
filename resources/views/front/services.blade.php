@extends('layouts.frontend')
@section('content')
<!-- Breadcroumbs start -->
<div class="wshipping-content-block wshipping-breadcroumb inner-bg-1">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-7">
                <h1>Services</h1>
            </div>
        </div>
    </div>
</div>
<!-- Breadcroumbs end -->

<!-- About content start -->
<div class="wshipping-content-block pt-4">
    <div class="container">
        <div class="row flex-lg-row-reverse">
            <div class="col-12">
                <div class="right-block mt-4">
                    <div class="inner-pagetitle text-center mt-5">
                        <h2 class="heading2-border">Need Shipping <span>World Wide</span></h2>
                    </div>

                    <div class="col-12">
                        <div class="service mt-4">
                            <div class="row">
                                <div class="col-6">
                                    <img src="{{ asset('frontend_assets') }}/images/service_01.jpg">
                                    <h4 class="text-primary border-bottom border-info pb-2">INTERNATIONAL SHIPMENT - AIR FREIGHT</h4>
                                    <p>When it comes to air freight shipping, weight and volume are key factors. International air freight moves anything that can be bought or sold. You can ship just about anything by air. Air freight shipments can be larger and may move across multiple carriers during shipment. Air freights can charge by either volumetric weight or actual weight, depending on which is more expensive. Letters, packages, cars, horses, construction equipment and even other airplanes can be shipped air freight. </p>

                                    <p class="mb-0">
                                        The advantages of shipping air freight are many, not just with the speed of delivery. With its unmatched speed, you can't afford to settle for less than air freight services as compared to sea and land freight will that will always be significantly slower and unreliable in that respect. Shipping via air freight means opening doors to more parts of the world and more customers served. You should always choose well-qualified, personable and experienced transporters over the cheapest available and that is how air freight works!
                                    </p>
                                </div>

                                <div class="col-6">
                                    <img src="{{ asset('frontend_assets') }}/images/service_02.jpg">
                                    <h4 class="text-primary border-bottom border-info pb-2">INTERNATIONAL SHIPMENT - SEA CARGO</h4>
                                    <p>
                                        Sea Cargo is the most commonly used shipping method in the globe. It's affordable and reliable. Cargo ships packed full of containers sail in and out every day, carrying shipments and from every corner of the world. The average cargo ship can carry around 18,000 containers! That's roomy. </p>

                                    <p class="mb-0">
                                        Reap the benefits of worldwide capacity, reliable sailing times and competitive rates to get your cargo where it needs when it needs to be on time. Depending on the size and weight if what you're shipping using a cargo ship is usually the most affordable shipping often. A cargo ship is one of the most eco-efficient ways to ship goods so, for this reason, it could be the best option you choose.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<!-- About content end -->
@endsection