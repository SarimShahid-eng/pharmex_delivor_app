@extends('layouts.frontend')
@section('content')
<style>
    input.parsley-success,
    select.parsley-success,
    textarea.parsley-success {
        color: #468847;
        background-color: #DFF0D8;
        border: 1px solid #D6E9C6;
    }

    input.parsley-error,
    select.parsley-error,
    textarea.parsley-error {
        color: #B94A48;
        background-color: #F2DEDE;
        border: 1px solid #EED3D7;
    }

    .parsley-errors-list {
        margin: 5px 0 3px;
        padding: 0;
        list-style-type: none;
        font-size: 0.9em;
        line-height: 0.9em;
        opacity: 0;
        color: #B94A48;

        transition: all .3s ease-in;
        -o-transition: all .3s ease-in;
        -moz-transition: all .3s ease-in;
        -webkit-transition: all .3s ease-in;
    }

    .parsley-errors-list.filled {
        opacity: 1;
    }
</style>
<!-- Breadcroumbs start -->
<div class="wshipping-content-block wshipping-breadcroumb inner-bg-1">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-7">
                <h1>Contact Us</h1>
            </div>
        </div>
    </div>
</div>
<!-- Breadcroumbs end -->

<!-- About content start -->
<div class="wshipping-content-block pb-2 pt-4">
    <div class="container">
        <div class="row flex-lg-row-reverse">
            <div class="col-12">
                <div class="right-block mt-4">
                    <div class="inner-pagetitle text-center mt-5">
                        <h2 class="heading2-border mb-5">Contact Us <span>Now</span></h2>
                    </div>

                    <div class="track_form">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-9 offset-md-2">
                                    <form method="post" class="ajaxForm" action="{{ route('front.contact.save') }}" novalidate>
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                                                <!-- <span class="text-danger">This Field is required</span> -->
                                            </div>
                                            <div class="col-md-6">
                                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                                                <!-- <span class="text-danger">This Field is required</span> -->
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <input type="text" name="phone" class="form-control" placeholder="Phone Number e.g: +923451234567" required>
                                                <!-- <span class="text-danger">This Field is required</span> -->
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="subject" placeholder="Your Subject" required>
                                                <!-- <span class="text-danger">This Field is required</span> -->
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <textarea class="form-control" name="msg" minlength="10" rows="7" required></textarea>
                                                <!-- <span class="text-danger">This Field is required</span> -->
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-12 text-center">
                                                <button class="btn btn-submit">Send Message</button>
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

    <div class="container">
        <div class="contactbtm_info">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled mt-4">
                            <li class="media my-3">
                                <div class="icon">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="mb-0 font-600 mt-3">{{ $_settings->contact->mobile ?? '-'}}</h4>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul class="list-unstyled mt-4">
                            <li class="media my-3">
                                <div class="icon">
                                    <i class="fa fa-envelope-o"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="mb-0 font-600 mt-3">{{ $_settings->contact->email ?? '-'}}</h4>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul class="list-unstyled mt-4">
                            <li class="media my-3">
                                <div class="icon">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="mb-0 font-600 mt-3">{{ $_settings->contact->address_state ?? '-'}}</h4>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About content end -->

<div class="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3604.987236913669!2d68.350991614488!3d25.371744230824074!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x394c71d15fd3d209%3A0x61cfb3965c3592d4!2sGexton!5e0!3m2!1sen!2s!4v1574422660881!5m2!1sen!2s"></iframe>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('frontend_assets') }}/js/parsley.min.js"></script>
<script src="{{ asset('frontend_assets') }}/js/sweetalert2.min.js"></script>
<script src="{{ asset('frontend_assets') }}/js/jquery.form.js"></script>
<script src="{{ asset('frontend_assets') }}/js/custom.js"></script>
@endsection