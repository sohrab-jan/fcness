@php
	$captcha = loadCustomCaptcha();
@endphp
@if($captcha)
    <div class="col-lg-12 form-group">
        <div class="captha">
            @php echo $captcha @endphp
        </div>
    </div>
    <div class="col-lg-12 form-group">
        <input type="text" name="captcha" class="form--control">
    </div>
@endif

@push('style')
<style>
    .captha div{
        width: 100% !important;
    }
</style>
@endpush
