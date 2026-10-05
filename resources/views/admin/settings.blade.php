@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </div>
            <h4 class="page-title">Settings</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">Settings</h4>
            <p class="text-muted font-14 m-b-20">
                Here you can change settings for frontend site.
            </p>

            <form action="{{ route('admin.settings.save') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <div class="row">
                    <div class="col-12">
                        <h4 class="border-bottom pb-1 mb-3 mt-2 text-info border-info">Contact Info</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="address_city">Address (City)<span class="text-danger">*</span></label>
                            <input type="text" name="contact[address_city]" parsley-trigger="change" required placeholder="Enter Address" class="form-control" id="address_city" value="{{ $settings->contact->address_city ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="address_state">State and Country<span class="text-danger">*</span></label>
                            <input type="text" name="contact[address_state]" parsley-trigger="change" required placeholder="Enter State and Country" class="form-control" id="address_state" value="{{ $settings->contact->address_state ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="phone">Phone<span class="text-danger">*</span></label>
                            <input type="text" name="contact[phone]" parsley-trigger="change" required placeholder="Enter Phone" class="form-control" id="phone" value="{{ $settings->contact->phone ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="mobile">Mobile<span class="text-danger">*</span></label>
                            <input type="text" name="contact[mobile]" parsley-trigger="change" required placeholder="Enter mobile" class="form-control" id="mobile" value="{{ $settings->contact->mobile ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="email">Email<span class="text-danger">*</span></label>
                            <input type="text" name="contact[email]" parsley-trigger="change" required placeholder="Enter Email" class="form-control" id="email" value="{{ $settings->contact->email ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="sat_thu">Business Hour (Sat - Thu)<span class="text-danger">*</span></label>
                            <input type="text" name="business_hours[sat_thu]" parsley-trigger="change" required placeholder="Enter Business Hour" class="form-control" id="sat_thu" value="{{ $settings->business_hours->sat_thu ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="friday">Business Hour (firday)<span class="text-danger">*</span></label>
                            <input type="text" name="business_hours[friday]" parsley-trigger="change" required placeholder="Enter Business Hour" class="form-control" id="friday" value="{{ $settings->business_hours->friday ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h4 class="border-bottom pb-1 mb-3 mt-4 text-info border-info">Social Links</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="facebook">Facebook</label>
                            <input type="text" name="socials[facebook]" parsley-trigger="change" placeholder="Enter Facebook Link" class="form-control" id="facebook" value="{{ $settings->social_links->facebook ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="twitter">Twitter</label>
                            <input type="text" name="socials[twitter]" parsley-trigger="change" placeholder="Enter twitter Link" class="form-control" id="twitter" value="{{ $settings->social_links->twitter ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="instagram">Instagram</label>
                            <input type="text" name="socials[instagram]" parsley-trigger="change" placeholder="Enter Instagram Link" class="form-control" id="instagram" value="{{ $settings->social_links->instagram ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="skype">Skype</label>
                            <input type="text" name="socials[skype]" parsley-trigger="change" placeholder="Enter Skype Link" class="form-control" id="skype" value="{{ $settings->social_links->skype ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="whatsapp">Whatsapp</label>
                            <input type="text" name="socials[whatsapp]" parsley-trigger="change" placeholder="Enter Whatsapp Link" class="form-control" id="whatsapp" value="{{ $settings->social_links->whatsapp ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="youtube">Youtube</label>
                            <input type="text" name="socials[youtube]" parsley-trigger="change" placeholder="Enter Youtube Link" class="form-control" id="youtube" value="{{ $settings->social_links->youtube ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <h4 class="border-bottom pb-1 mb-3 mt-4 text-info border-info">About Text</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="footer_about">Footer About Text<span class="text-danger">*</span></label>
                            <textarea name="footer_about" rows="8" parsley-trigger="change" required placeholder="Enter footer about text which will show under logo on footer" class="form-control" id="footer_about">{{ $settings->footer_about ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3 text-right">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        Submit
                    </button>
                    <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                        Cancel
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection