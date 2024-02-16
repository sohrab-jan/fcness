@extends($activeTemplate.'layouts.frontend')

@php
    $authImage = getContent('auth_image.content', true);
    $policyPages = getContent('policy_pages.element');
@endphp

@section('content')

<!-- registration section start -->
<section class="registration-section pt-100 pb-100">
    <div class="el-1"><img src="{{ getImage('assets/images/frontend/auth_image/' .@$authImage->data_values->image_one, '656x860') }}" alt="@lang('image')"></div>
    <div class="el-2"><img src="{{ getImage('assets/images/frontend/auth_image/' .@$authImage->data_values->image_two, '656x860') }}" alt="@lang('image')"></div>
    <div class="container">
        <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="registration-wrapper section--bg">
            <form class="transparent-form" action="{{ route('user.register') }}" method="POST" onsubmit="return submitUserForm();">
                @csrf
                <div class="row">

                @if(session()->get('reference') != null)
                <div class="col-lg-12 form-group">
                    <label for="referenceBy">@lang('Reference By') <sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                    <i class="las la-user"></i>
                    <input type="text" name="referBy" id="referenceBy" class="form--control" value="{{session()->get('reference')}}" readonly>
                    </div>
                </div>
                @endif

                <div class="col-lg-6 form-group">
                    <label for="firstname">@lang('First Name') <sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                    <i class="las la-user"></i>
                    <input id="firstname" type="text" class="form--control" name="firstname" value="{{ old('firstname') }}" required placeholder="@lang('First Name')">
                    </div>
                </div>
                <div class="col-lg-6 form-group">
                    <label for="lastname">@lang('Last Name') <sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                    <i class="las la-user"></i>
                    <input id="lastname" type="text" class="form--control" name="lastname" value="{{ old('lastname') }}" required placeholder="@lang('Last Name')">
                    </div>
                </div>
                <div class="col-lg-6 form-group">
                    <label>@lang('Country') <sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                    <i class="las la-flag"></i>
                    <select name="country" id="country" class="form--control">
                        @foreach($countries as $key => $country)
                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="col-lg-6 form-group">
                    <label>@lang('Mobile') <sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                    <i class="las la-envelope"></i>
                    <div class="input-group">
                        <span class="input-group-text mobile-code">
                        </span>
                        <input type="hidden" name="mobile_code">
                        <input type="hidden" name="country_code">
                        <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}" class="form--control checkUser">
                    </div>
                    <small class="text-danger mobileExist"></small>
                    </div>
                </div>
                <div class="col-lg-6 form-group">
                    <label for="username">@lang('Username') <sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                        <i class="las la-user"></i>
                        <input id="username" type="text" class="form--control checkUser" name="username" required placeholder="@lang('Username')">
                        <small class="text-danger usernameExist"></small>
                    </div>
                </div>
                <div class="col-lg-6 form-group">
                    <label for="email">@lang('E-Mail Address') <sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                    <i class="las la-envelope"></i>
                    <input id="email" type="email" class="form--control checkUser" name="email" value="{{ old('email') }}" required placeholder="@lang('Email')">
                    </div>
                </div>
                <div class="col-lg-6 form-group hover-input-popup">
                    <label for="password">@lang('Password') <sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                    <i class="las la-key"></i>
                    <input id="password" type="password" class="form--control" name="password" required placeholder="@lang('Password')">
                    @if($general->secure_password)
                        <div class="input-popup">
                            <p class="error lower">@lang('1 small letter minimum')</p>
                            <p class="error capital">@lang('1 capital letter minimum')</p>
                            <p class="error number">@lang('1 number minimum')</p>
                            <p class="error special">@lang('1 special character minimum')</p>
                            <p class="error minimum">@lang('6 character password')</p>
                        </div>
                    @endif
                    </div>
                </div>
                <div class="col-lg-6 form-group">
                    <label for="password-confirm">@lang('Confirm Password') <sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                    <i class="las la-key"></i>
                    <input id="password-confirm" type="password" class="form--control" name="password_confirmation" required autocomplete="new-password" placeholder="@lang('Confirm Password')">
                    </div>
                </div>

                <div class="col-lg-12 form-group">
                    @php echo loadReCaptcha() @endphp
                </div>
                @include($activeTemplate.'partials.custom_captcha')

                @if($general->agree)
                <div class="col-lg-6 form-group">
                    <div class="col-md-12">
                        <input type="checkbox" id="agree" name="agree">
                        <label for="agree">
                            @lang('I agree with ')
                            @foreach($policyPages as $policyPage)
                                <a href="{{route('policy.details',['policy'=>slug($policyPage->data_values->title), 'id'=>$policyPage->id])}}" target="_blank" class="text--base">
                                    {{__($policyPage->data_values->title)}}
                                </a>
                                {{ $loop->last ? '' : ', ' }}
                            @endforeach
                        </label>
                    </div>
                </div>
                @endif

                <div class="col-lg-12">
                    <button type="submit" class="btn btn--base w-100">@lang('Sign Up')</button>
                    <p class="text-center mt-3"> @lang('Already you have an account') ? <a href="{{ route('user.login') }}" class="text--base">@lang('Login Now')</a></p>
                </div>
                </div>
            </form>
            </div>
        </div>
        </div>
    </div>
</section>
<!-- registration section end -->

<div class="modal login-modal fade" id="existModalCenter" tabindex="-1" aria-labelledby="existModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-relative section--bg">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

        <div class="login-wrapper text-center">
            <div class="login-wrapper__icon bg--base text-white">
            <i class="las la-sign-in-alt"></i>
            </div>
            <div class="login-wrapper__content mt-3">
            <h6 class="text-center">@lang('You already have an account please Sign in')</h6>
            <div class="modal-footer">
                <button type="button" class="btn btn--danger btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                <a href="{{ route('user.login') }}" class="btn btn--success btn-sm">@lang('Login')</a>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>

@endsection
@push('style')
<style>
    .country-code .input-group-prepend .input-group-text{
        background: #fff !important;
    }
    .country-code select{
        border: none;
    }
    .country-code select:focus{
        border: none;
        outline: none;
    }
    .hover-input-popup {
        position: relative;
    }
    .hover-input-popup:hover .input-popup {
        opacity: 1;
        visibility: visible;
    }
    .input-popup {
        position: absolute;
        bottom: 130%;
        left: 50%;
        width: 280px;
        background-color: #1a1a1a;
        color: #fff;
        padding: 20px;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        -ms-border-radius: 5px;
        -o-border-radius: 5px;
        -webkit-transform: translateX(-50%);
        -ms-transform: translateX(-50%);
        transform: translateX(-50%);
        opacity: 0;
        visibility: hidden;
        -webkit-transition: all 0.3s;
        -o-transition: all 0.3s;
        transition: all 0.3s;
    }
    .input-popup::after {
        position: absolute;
        content: '';
        bottom: -19px;
        left: 50%;
        margin-left: -5px;
        border-width: 10px 10px 10px 10px;
        border-style: solid;
        border-color: transparent transparent #1a1a1a transparent;
        -webkit-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        transform: rotate(180deg);
    }
    .input-popup p {
        padding-left: 20px;
        position: relative;
    }
    .input-popup p::before {
        position: absolute;
        content: '';
        font-family: 'Line Awesome Free';
        font-weight: 900;
        left: 0;
        top: 4px;
        line-height: 1;
        font-size: 18px;
    }
    .input-popup p.error {
        text-decoration: line-through;
    }
    .input-popup p.error::before {
        content: "\f057";
        color: #ea5455;
    }
    .input-popup p.success::before {
        content: "\f058";
        color: #28c76f;
    }
</style>
@endpush
@push('script-lib')
<script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
@push('script')
    <script>
      "use strict";
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
        (function ($) {
            @if($mobile_code)
            $(`option[data-code={{ $mobile_code }}]`).attr('selected','');
            @endif

            $('select[name=country]').change(function(){
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            @if($general->secure_password)
                $('input[name=password]').on('input',function(){
                    secure_password($(this));
                });
            @endif

            $('.checkUser').on('focusout',function(e){
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {mobile:mobile,_token:token}
                }
                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }
                if ($(this).attr('name') == 'username') {
                    var data = {username:value,_token:token}
                }
                $.post(url,data,function(response) {
                  if (response['data'] && response['type'] == 'email') {
                    $('#existModalCenter').modal('show');
                  }else if(response['data'] != null){
                    $(`.${response['type']}Exist`).text(`${response['type']} already exist`);
                  }else{
                    $(`.${response['type']}Exist`).text('');
                  }
                });
            });

        })(jQuery);

    </script>
@endpush
