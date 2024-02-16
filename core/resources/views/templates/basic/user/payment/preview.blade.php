@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row  justify-content-center">
            <div class="col-md-5">
                <div class="custom--card card-deposit text-center">
                    <div class="card-body card-body-deposit">
                        <ul class="list-group text-center">
                            <li>
                                <img src="{{ $data->gatewayCurrency()->methodImage() }}" alt="@lang('Image')" class="w-100" />
                            </li>
                            <li>
                                @lang('Amount'):
                                <strong>{{showAmount($data->amount)}} </strong> {{__($general->cur_text)}}
                            </li>
                            <li>
                                @lang('Charge'):
                                <strong>{{showAmount($data->charge)}}</strong> {{__($general->cur_text)}}
                            </li>
                            <li>
                                @lang('Payable'): <strong> {{showAmount($data->amount + $data->charge)}}</strong> {{__($general->cur_text)}}
                            </li>
                            <li>
                                @lang('Conversion Rate'): <strong>1 {{__($general->cur_text)}} = {{showAmount($data->rate)}}  {{__($data->baseCurrency())}}</strong>
                            </li>
                            <li>
                                @lang('In') {{$data->baseCurrency()}}:
                                <strong>{{showAmount($data->final_amo)}}</strong>
                            </li>

                            @if($data->gateway->crypto==1)
                                <li>
                                    @lang('Conversion with')
                                    <b> {{ __($data->method_currency) }}</b> @lang('and final value will Show on next step')
                                </li>
                            @endif
                        </ul>

                        @if( 1000 >$data->method_code)
                            <a href="{{route('user.deposit.confirm')}}" class="btn btn--success btn-block py-3 fw-bold w-100 text-center mt-4">@lang('Pay Now')</a>
                        @else
                            <a href="{{route('user.deposit.manual.confirm')}}" class="btn btn--success btn-block py-3 fw-bold w-100 text-center mt-4">@lang('Pay Now')</a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection


