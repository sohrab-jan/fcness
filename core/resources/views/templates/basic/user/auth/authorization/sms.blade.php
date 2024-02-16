@extends($activeTemplate .'layouts.master')

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
                <form class="transparent-form" action="{{route('user.verify.sms')}}" method="post">
                 @csrf
                  <div class="row">

                    <div class="col-lg-12 form-group">
                      <label>
                        <h6 class="text-center">@lang('Your Mobile Number'):  <strong>{{auth()->user()->mobile}}</strong></h6>
                      </label>
                    </div>

                    <div class="col-lg-12 form-group">
                      <label>@lang('Verification Code') <sup class="text--danger">*</sup></label>
                      <div class="custom-icon-field">
                        <i class="las la-code"></i>
                        <input type="text" name="sms_verified_code" id="code" class="form--control">
                      </div>
                    </div>

                    <div class="col-lg-12">
                      <button type="submit" class="btn btn--base w-100 mt-4">@lang('Submit')</button>
                        <p class="mt-3">@lang('If you don\'t get any code')
                            <a href="{{route('user.send.verify.code')}}?type=phone" class="text--base">
                                @lang('Try again')
                            </a>
                        </p>
                        @if ($errors->has('resend'))
                            <small class="text-danger">{{ $errors->first('resend') }}</small>
                        @endif
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
