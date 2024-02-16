@extends($activeTemplate.'layouts.frontend')

@php
    $authImage = getContent('auth_image.content', true);
@endphp

@section('content')
<section class="registration-section pt-100 pb-100">
    <div class="el-1"><img src="{{ getImage('assets/images/frontend/auth_image/' .@$authImage->data_values->image_one, '656x860') }}" alt="@lang('image')"></div>
    <div class="el-2"><img src="{{ getImage('assets/images/frontend/auth_image/' .@$authImage->data_values->image_two, '656x860') }}" alt="@lang('image')"></div>
    <div class="container">
        <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="registration-wrapper section--bg">
            <form class="transparent-form" action="{{ route('user.password.verify.code') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <div class="row">
                    <div class="col-lg-12 form-group">
                        <label for="code">@lang('Verification Code') <sup class="text--danger">*</sup></label>
                        <div class="custom-icon-field">
                        <i class="las la-code"></i>
                        <input type="text" name="code" id="code" class="form--control">
                    </div>

                    <div class="col-lg-12 form-group mt-4">
                        <button type="submit" class="btn btn--base w-100">@lang('Verify Code')</button>
                        <p class="mt-3"> @lang("Please check including your Junk/Spam Folder. if not found, you can")
                            <a href="{{ route('user.password.request') }}" class="text--base">
                                @lang('Try to send again')
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
@endsection
@push('script')
<script>
    (function($){
        "use strict";
        $('#code').on('input change', function () {
          var xx = document.getElementById('code').value;
          $(this).val(function (index, value) {
             value = value.substr(0,7);
              return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
          });
      });
    })(jQuery)
</script>
@endpush
