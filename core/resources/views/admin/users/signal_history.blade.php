@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('SL')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Details')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($signals as $data)
                            <tr>
                                <td data-label="@lang('SL')">
                                    {{ $loop->index + 1 }}
                                </td>

                                <td data-label="@lang('Name')">{{ shortDescription($data->name, 60) }}</td>

                                <td data-label="@lang('Date')">{{ showDateTime($data->created_at) }}</td>

                                <td data-label="@lang('Action')">
                                    <a href="javascript:void(0)" class="icon-btn signalBtn" data-toggle="tooltip" title="" data-original-title="@lang('Signal')"
                                        data-signal="{{ $data->signal }}"
                                        data-name="{{ $data->name }}"
                                    >
                                        <i class="las la-desktop text--shadow"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($signals) }}
                </div>
            </div>
        </div>
    </div>

<div id="signalModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">@lang('Details')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="nameArea">
                    <h6 class="text-dark font-weight-bold">@lang('Signal Name')</h6>
                    <p class="name text-dark"></p>
                </div>
                <div class="signalArea mt-3">
                    <h6 class="text-dark font-weight-bold">@lang('Signal Details')</h6>
                    <p class="signal text-dark"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.signalBtn').on('click', function() {
                var modal = $('#signalModal');
                modal.find('.name').text($(this).data('name'));
                modal.find('.signal').text($(this).data('signal'));
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
