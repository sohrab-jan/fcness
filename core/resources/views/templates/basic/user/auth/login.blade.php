@extends($activeTemplate.'layouts.frontend')

@php
    $authImage = getContent('auth_image.content', true);
@endphp

@section('content')
<!-- Login section start -->
<section class="registration-section pt-100 pb-100">
    <div class="el-1"><img src="{{ getImage('assets/images/frontend/auth_image/' .@$authImage->data_values->image_one, '656x860') }}" alt="@lang('image')"></div>
    <div class="el-2"><img src="{{ getImage('assets/images/frontend/auth_image/' .@$authImage->data_values->image_two, '656x860') }}" alt="@lang('image')"></div>
    <div class="container">
        <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="registration-wrapper section--bg">
            <form class="transparent-form" method="POST" action="{{ route('user.login')}}" onsubmit="return submitUserForm();">
                @csrf
                <div class="row">
                    <div class="col-lg-12 form-group">
                        <label>@lang('Username or Email') <sup class="text--danger">*</sup></label>
                        <div class="custom-icon-field">
                        <i class="las la-user"></i>
                        <input type="text" name="username" value="{{ old('username') }}" class="form--control" placeholder="@lang('Enter username')" required>
                    </div>
                    <div class="col-lg-12 form-group mt-4">
                        <label>@lang('Password') <sup class="text--danger">*</sup></label>
                        <div class="custom-icon-field">
                        <i class="las la-key"></i>
                        <input id="password" type="password" class="form--control" name="password" placeholder="@lang('Enter password')" required>
                        </div>
                    </div>
                    <div class="col-lg-12 form-group">
                        @php echo loadReCaptcha() @endphp
                    </div>
                    @include($activeTemplate.'partials.custom_captcha')

                    <div class="col-lg-12 form-group">
                        <p class="text-end">
                            <a href="{{ route('user.password.request') }}" class="text--base">
                                @lang("Forgot Your Password")?
                            </a>
                        </p>
                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn--base w-100">@lang('Sign In')</button>
                        <p class="text-center mt-3"> @lang("Don't have an account")?
                            <a href="{{ route('user.register') }}" class="text--base">
                                @lang('Create Account')
                            </a>
                        </p>
                    </div>
                </div>
            </form>
            </div>
        </div>
        </div>
    </div>
</section>
<!-- Login section end -->
@endsection

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
    </script>
@endpush
