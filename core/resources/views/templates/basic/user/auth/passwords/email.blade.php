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
            <form class="transparent-form" method="POST" action="{{ route('user.password.email') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12 form-group">
                        <label>@lang('Select One') <sup class="text--danger">*</sup></label>
                        <select class="form--control" name="type">
                            <option value="email">@lang('E-Mail Address')</option>
                            <option value="username">@lang('Username')</option>
                        </select>
                    </div>
                    </div>
                    <div class="col-lg-12 form-group">
                        <label class="my_value"></label>
                        <input type="text" class="form--control @error('value') is-invalid @enderror" name="value" value="{{ old('value') }}" required autofocus="off">
                        <div class="custom-icon-field">
                        @error('value')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-lg-12 form-group mt-4">
                        <button type="submit" class="btn btn--base w-100">@lang('Send Password Code')</button>
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

        myVal();
        $('select[name=type]').on('change',function(){
            myVal();
        });
        function myVal(){
            $('.my_value').text($('select[name=type] :selected').text());
        }
    })(jQuery)
</script>
@endpush
