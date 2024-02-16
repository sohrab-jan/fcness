@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container pt-100 pb-100">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="custom--card text-center">
                    <div class="card-body">
                        <h2 class="title mb-3"><span>@lang('Payment Preview')</span></h2>
                        <img src="{{$deposit->gatewayCurrency()->methodImage()}}" class="card-img-top" alt="@lang('Image')" class="w-100">
                        <form action="{{ route('ipn.'.$deposit->gateway->alias) }}" method="POST" class="text-center mt-3">
                            @csrf
                            <h3>@lang('Please Pay') {{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</h3>
                            <h3 class="my-3">@lang('To Get') {{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</h3>
                            <button type="button" class=" mt-4 btn btn--success w-100" id="btn-confirm">@lang('Pay Now')</button>
                            <script
                                src="//js.paystack.co/v1/inline.js"
                                data-key="{{ $data->key }}"
                                data-email="{{ $data->email }}"
                                data-amount="{{$data->amount}}"
                                data-currency="{{$data->currency}}"
                                data-ref="{{ $data->ref }}"
                                data-custom-button="btn-confirm"
                            >
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
