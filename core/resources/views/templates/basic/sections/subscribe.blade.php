@php
    $subscribe = getContent('subscribe.content', true);
@endphp

<!-- subscribe section start -->
<section class="subscribe-section section--bg">
    <div class="container">
        <div class="row gy-4 align-items-center">
        <div class="col-lg-6 text-lg-start text-center wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.3s">
            <h2>{{ __(@$subscribe->data_values->heading) }}</h2>
        </div>
        <div class="col-lg-6 wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.5s">
            <form class="subscribe-form">
                    @csrf
                <div class="custom-icon-field">
                    <i class="las la-envelope"></i>
                    <input type="email" name="email" autocomplete="off" class="form--control" placeholder="@lang('Enter email address')" required>
                </div>
                <button type="submit" class="subscribe-btn">{{ __(@$subscribe->data_values->button_text) }} <i class="lab la-telegram-plane"></i></button>
            </form>
        </div>
        </div>
    </div>
</section>
<!-- subscribe section end -->

@push('script')

    <script>
        (function($){

            "use strict";

            var formEl = $(".subscribe-form");

            formEl.on('submit', function(e){
                e.preventDefault();
                var data = formEl.serialize();

                $.ajax({
                url:"{{ route('subscribe') }}",
                method:'post',
                data:data,

                success:function(response){
                    if(response.success){
                        formEl.find('input[name=email]').val('')
                        notify('success', response.message);
                    }else{
                        $.each(response.error, function( key, value ) {
                            notify('error', value);
                        });
                    }
                },
                error:function(error){
                    console.log(error)
                }

                });
            });

        })(jQuery);
    </script>

@endpush

