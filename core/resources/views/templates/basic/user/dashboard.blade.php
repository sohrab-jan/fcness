@extends($activeTemplate.'layouts.master')
@section('content')
<!-- dashboard section start -->
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center gy-4">
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.trx.history') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <i class="las la-money-bill-wave text--base"></i>
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Total Balance')</p>
                        <h3 class="d-widget__amount mt-1">
                            {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                        </h3>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="javascript:void(0)" class="item--link {{ $user->package_id != 0 ? 'renewBtn' : null }}"
                        @if($user->package_id != 0)
                            data-package="{{ @$user->package }}"
                        @endif
                    ></a>
                    <div class="d-widget__icon v">
                        <i class="las la-calendar text--base"></i>
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">
                            @if($user->package_id != 0)
                                {{ __(@$user->package->name) }}
                            @else
                                @lang('Package')
                            @endif
                        </p>
                        <div class="d-flex align-items-center">
                            <h3 class="d-widget__amount mt-1">
                                @if($user->package_id != 0)
                                    {{ showDateTime($user->validity, 'd M Y') }}
                                @else
                                    @lang('N/A')
                                @endif
                            </h3>
                            <small class="d-widget__caption ms-2">(@lang('Validity'))</small>
                        </div>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('ticket') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <i class="las la-ticket-alt text--base"></i>
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Total Ticket')</p>
                        <h3 class="d-widget__amount mt-1">
                            {{ $totalTicket }}
                        </h3>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.signal.history') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <i class="las la-signal text--base"></i>
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Total Signal')</p>
                        <h3 class="d-widget__amount mt-1">
                            {{ $totalSignal }}
                        </h3>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.trx.history') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <i class="las la-exchange-alt text--base"></i>
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Total Transaction')</p>
                        <h3 class="d-widget__amount mt-1">
                            {{ $totalTrx }}
                        </h3>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.deposit.history') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <i class="las la-wallet text--base"></i>
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Total Deposit')</p>
                        <h3 class="d-widget__amount mt-1">
                            {{ $general->cur_sym }} {{ showAmount($totalDeposit, 2) }}
                        </h3>
                    </div>
                </div><!-- d-widget end -->
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-lg-12">
                <h5 class="m-4 text-center">@lang('Latest Transaction')</h5>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                            <tr>
                                <th>@lang('Date')</th>
                                <th>@lang('Trx')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Post Balance')</th>
                                <th>@lang('Details')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($latestTrxs as $data)
                                <tr>
                                    <td data-label="@lang('Date')">
                                        {{ showDateTime($data->created_at) }}
                                    </td>
                                    <td data-label="@lang('Trx')">{{ $data->trx }}</td>
                                    <td data-label="@lang('Amount')">
                                        <strong>
                                            {{ $data->trx_type }}
                                            {{ showAmount($data->amount) }}
                                            {{ __($general->cur_text) }}
                                        </strong>
                                    </td>
                                    <td data-label="@lang('Post Balance')">
                                        <strong>
                                            {{ showAmount($data->post_balance) }}
                                            {{ __($general->cur_text) }}
                                        </strong>
                                    </td>
                                    <td data-label="@lang('Details')">{{ __($data->details) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center justify-content-center">@lang('Data Not Found')!</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- dashboard section end -->
@if($user->package_id != 0)
    <div class="modal fade cmn--modal" id="renewModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title method-name"></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.renew.package')}}" method="post">
                    @csrf
                    <div class="modal-body pt-0">
                        <div class="form-group">
                            <input type="hidden" name="id" required>
                        </div>
                        <ul class="list-group">
                            <li>@lang('Package') : <span class="packageName fw-bold"></span></li>
                            <li>@lang('Price') : <span class="packagePrice fw-bold"></span></li>
                            <li>@lang('Validity') : <span class="packageValidity fw-bold"></span></li>
                            <li>@lang('Your Balance') : <span class="fw-bold">{{ showAmount($user->balance, 2) }} {{ __($general->cur_text) }} </span></li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <div class="prevent-double-click">
                            <button type="submit" class="btn btn-sm btn--success">@lang('Confirm')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection

@if($user->package_id != 0)
    @push('script')
    <script>

        (function ($) {
            "use strict";

            $('.renewBtn').on('click', function () {
                var modal = $('#renewModal');

                modal.find('.modal-title').text('Are you sure to renew '+$(this).data('package').name);
                modal.find('input[name=id]').val($(this).data('package').id);

                modal.find('.packageName').text($(this).data('package').name);
                modal.find('.packagePrice').text($(this).data('package').price+' '+@json( __($general->cur_text) ));
                modal.find('.packageValidity').text($(this).data('package').validity+' Days');

                modal.modal('show');
            });

        })(jQuery);

    </script>
    @endpush
@endif