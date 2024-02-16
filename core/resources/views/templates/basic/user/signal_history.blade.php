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
                        <th>@lang('SL')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Details')</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($logs as $data)
                        <tr>
                            <td data-label="@lang('SL')">
                                {{ $loop->index + 1 }}
                            </td>
                            <td data-label="@lang('Name')">{{ shortDescription($data->name, 60) }}</td>
                            <td data-label="@lang('Date')">{{ showDateTime($data->created_at) }}</td>
                            <td data-label="@lang('Details')">
                                <a href="javascript:void(0)" class="icon-btn bg--primary signalBtn" data-bs-toggle="tooltip" data-bs-position="top" title="@lang('Signal')"
                                    data-signal="{{ $data->signal }}"
                                    data-name="{{ $data->name }}"
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

<div id="signalModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ">@lang('Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="nameArea">
                    <h6 class=" fw-bold">@lang('Signal Name')</h6>
                    <p class="name "></p>
                </div>
                <div class="signalArea mt-3">
                    <h6 class=" fw-bold">@lang('Signal Details')</h6>
                    <p class="signal "></p>
                </div>
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

            $('.signalBtn').on('click', function() {
                var modal = $('#signalModal');
                modal.find('.name').text($(this).data('name'));
                modal.find('.signal').text($(this).data('signal'));
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
