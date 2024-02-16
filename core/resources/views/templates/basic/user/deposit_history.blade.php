@extends($activeTemplate.'layouts.master')
@section('content')

<section class="pt-100 pb-100">
    <div class="container">

      <div class="row">
        <div class="col-lg-12">
          <div class="custom--card">
            <div class="card-body p-0">
              <div class="table-responsive--md">
                <table class="table custom--table">
                  <thead>
                    <tr>
                        <th>@lang('Transaction ID')</th>
                        <th>@lang('Gateway')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Time')</th>
                        <th>@lang('Details')</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($logs as $k=>$data)
                    <tr>
                        <td data-label="#@lang('Trx')">{{$data->trx}}</td>
                        <td data-label="@lang('Gateway')">{{ __(@$data->gateway->name)  }}</td>
                        <td data-label="@lang('Amount')">
                            <strong>{{showAmount($data->amount)}} {{__($general->cur_text)}}</strong>
                        </td>
                        <td>
                            @if($data->status == 1)
                                <span class="badge badge--success">@lang('Complete')</span>
                            @elseif($data->status == 2)
                                <span class="badge badge--warning">@lang('Pending')</span>
                            @elseif($data->status == 3)
                                <span class="badge badge--danger">@lang('Cancel')</span>
                            @endif

                            @if($data->admin_feedback != null)
                                <button class="btn-info btn-rounded  badge detailBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="fa fa-info"></i></button>
                            @endif

                        </td>
                        <td data-label="@lang('Time')">
                            {{showDateTime($data->created_at)}}
                        </td>

                        @php
                            $details = ($data->detail != null) ? json_encode($data->detail) : null;
                        @endphp

                        <td data-label="@lang('Details')">
                            <a href="#0" class="icon-btn bg--primary approveBtn" data-bs-toggle="tooltip" data-bs-position="top" title="@lang('Details')"
                            data-info="{{ $details }}"
                            data-id="{{ $data->id }}"
                            data-amount="{{ showAmount($data->amount)}} {{ __($general->cur_text) }}"
                            data-charge="{{ showAmount($data->charge)}} {{ __($general->cur_text) }}"
                            data-after_charge="{{ showAmount($data->amount + $data->charge)}} {{ __($general->cur_text) }}"
                            data-rate="{{ showAmount($data->rate)}} {{ __($data->method_currency) }}"
                            data-payable="{{ showAmount($data->final_amo)}} {{ __($data->method_currency) }}"
                            >

                                <i class="las la-desktop"></i>
                            </a>
                        </td>
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

      <div class="mt-4 justify-content-center d-flex">
          {{ $logs->links() }}
      </div>

    </div>
</section>

{{-- APPROVE MODAL --}}
<div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ">@lang('Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul>
                    <li>@lang('Amount') : <span class="withdraw-amount fw-bold"></span></li>
                    <li>@lang('Charge') : <span class="withdraw-charge fw-bold"></span></li>
                    <li>@lang('After Charge') : <span class="withdraw-after_charge fw-bold"></span></li>
                    <li>@lang('Conversion Rate') : <span class="withdraw-rate fw-bold"></span></li>
                    <li>@lang('Payable Amount') : <span class="withdraw-payable fw-bold"></span></li>
                </ul>
                <ul class="list-group withdraw-detail mt-1">
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--danger btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

{{-- Detail MODAL --}}
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ">@lang('Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="withdraw-detail "></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--danger btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.find('.withdraw-charge').text($(this).data('charge'));
                modal.find('.withdraw-after_charge').text($(this).data('after_charge'));
                modal.find('.withdraw-rate').text($(this).data('rate'));
                modal.find('.withdraw-payable').text($(this).data('payable'));
                var list = [];
                var details =  Object.entries($(this).data('info'));

                var ImgPath = "{{asset(imagePath()['verify']['deposit']['path'])}}/";
                var singleInfo = '';
                for (var i = 0; i < details.length; i++) {
                    if (details[i][1].type == 'file') {
                        singleInfo += `<li>
                                            <span class="fw-bold"> ${details[i][0].replaceAll('_', " ")} </span> : <img src="${ImgPath}/${details[i][1].field_name}" alt="@lang('Image')" class="w-100">
                                        </li>`;
                    }else{
                        singleInfo += `<li>
                                            <span class="fw-bold"> ${details[i][0].replaceAll('_', " ")} </span> : <span class="font-weight-bold ml-3">${details[i][1].field_name}</span>
                                        </li>`;
                    }
                }

                if (singleInfo)
                {
                    modal.find('.withdraw-detail').html(`<br><strong class="my-3 ">@lang('Payment Information')</strong>  ${singleInfo}`);
                }else{
                    modal.find('.withdraw-detail').html(`${singleInfo}`);
                }
                modal.modal('show');
            });

            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var feedback = $(this).data('admin_feedback');
                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('style')
<style>
    .list-group-item{
        border:none;
    }
</style>
@endpush
