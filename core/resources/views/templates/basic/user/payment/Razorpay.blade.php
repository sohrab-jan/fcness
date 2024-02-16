@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="container pt-100 pb-100">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="custom--card">
                    <div class="card-body">
                        <img src="{{$deposit->gatewayCurrency()->methodImage()}}" class="card-img-top" alt="@lang('Image')" class="w-100">
                        <h3 class="text-center">@lang('Please Pay') {{showAmount($deposit->final_amo)}} {{$deposit->method_currency}}</h3>
                        <h3 class="my-3 text-center">@lang('To Get') {{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</h3>
                        <form action="{{$data->url}}" method="{{$data->method}}">
                            <input type="hidden" custom="{{$data->custom}}" name="hidden">
                            <script src="{{$data->checkout_js}}"
                                    @foreach($data->val as $key=>$value)
                                    data-{{$key}}="{{$value}}"
                                @endforeach >
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('input[type="submit"]').addClass("mt-4 btn btn--success w-100");
        })(jQuery);
    </script>
@endpush
