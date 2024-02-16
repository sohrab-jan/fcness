@extends($activeTemplate.'layouts.master')

@section('content')
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="custom--card">
                    <div class="card-body">
                        <form class="transparent-form register prevent-double-click" action="" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group text-center">
                                        <div class="avatar-upload">
                                            <div class="avatar-preview">
                                                <div id="imagePreview" style="background-image: url({{ getImage(imagePath()['profile']['user']['path'].'/'. $user->image,imagePath()['profile']['user']['size']) }});">
                                                </div>
                                                <div class="avatar-edit">
                                                    <input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                                    <label for="imageUpload"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <code class="text--base">image size 350 X 300</code>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="InputFirstname" >@lang('First Name'):</label>
                                    <input type="text" class="form--control" id="InputFirstname" name="firstname" placeholder="@lang('First Name')" value="{{$user->firstname}}" minlength="3">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="lastname" >@lang('Last Name'):</label>
                                    <input type="text" class="form--control" id="lastname" name="lastname" placeholder="@lang('Last Name')" value="{{$user->lastname}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="email" >@lang('E-mail Address'):</label>
                                    <input class="form--control" id="email" placeholder="@lang('E-mail Address')" value="{{$user->email}}" readonly>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="phone" >@lang('Mobile Number')</label>
                                    <input class="form--control" id="phone" value="{{$user->mobile}}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="address" >@lang('Address'):</label>
                                    <input type="text" class="form--control" id="address" name="address" placeholder="@lang('Address')" value="{{@$user->address->address}}" required="">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="state" >@lang('State'):</label>
                                    <input type="text" class="form--control" id="state" name="state" placeholder="@lang('state')" value="{{@$user->address->state}}" required="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="zip" >@lang('Zip Code'):</label>
                                    <input type="text" class="form--control" id="zip" name="zip" placeholder="@lang('Zip Code')" value="{{@$user->address->zip}}" required="">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="city" >@lang('City'):</label>
                                    <input type="text" class="form--control" id="city" name="city" placeholder="@lang('City')" value="{{@$user->address->city}}" required="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label >@lang('Country'):</label>
                                    <input class="form--control" value="{{@$user->address->country}}" disabled>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label >@lang('Telegram Username'):</label>
                                    <input class="form--control" value="{{ @$user->telegram_username }}" name="telegram">
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="col-sm-12 text-center mt-3">
                                    <button type="submit" class="btn btn-block btn--success w-100 text-center">@lang('Update Profile')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue.'css/bootstrap-fileinput.css') }}" rel="stylesheet">
@endpush

@push('style')
    <link rel="stylesheet" href="{{asset('assets/admin/build/css/intlTelInput.css')}}">
    <style>
        .intl-tel-input {
            position: relative;
            display: inline-block;
            width: 100%;!important;
        }
    </style>
@endpush


@push('script')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                    $('#imagePreview').hide();
                    $('#imagePreview').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imageUpload").change(function() {
            readURL(this);
        });

    </script>
@endpush
