@extends($activeTemplate.'layouts.master')

@section('content')
<section class="pt-100 pb-100">
    <div class="container">

      <div class="row mt-5">
        <div class="col-lg-12">
          <div class="custom--card">
            <div class="card-body p-0">
              <div class="table-responsive--md">
                <table class="table custom--table">
                  <thead>
                    <tr>
                        <th>@lang('Subject')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Priority')</th>
                        <th>@lang('Last Reply')</th>
                        <th>@lang('Action')</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($supports as $key => $support)
                    <tr>
                        <td data-label="@lang('Subject')">
                            <a href="{{ route('ticket.view', $support->ticket) }}" class="fw-bold">
                                [@lang('Ticket')#{{ $support->ticket }}]
                                <span class="text-white">{{ __($support->subject) }}</span>
                            </a>
                        </td>
                        <td data-label="@lang('Status')">
                            @if($support->status == 0)
                                <span class="badge badge--success py-2 px-3">@lang('Open')</span>
                            @elseif($support->status == 1)
                                <span class="badge badge--primary py-2 px-3">@lang('Answered')</span>
                            @elseif($support->status == 2)
                                <span class="badge badge--warning py-2 px-3">@lang('Customer Reply')</span>
                            @elseif($support->status == 3)
                                <span class="badge badge--danger py-2 px-3">@lang('Closed')</span>
                            @endif
                        </td>
                        <td data-label="@lang('Priority')">
                            @if($support->priority == 1)
                                <span class="badge badge--dark py-2 px-3">@lang('Low')</span>
                            @elseif($support->priority == 2)
                                <span class="badge badge--success py-2 px-3">@lang('Medium')</span>
                            @elseif($support->priority == 3)
                                <span class="badge badge--primary py-2 px-3">@lang('High')</span>
                            @endif
                        </td>
                        <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                        <td data-label="@lang('Action')">
                            <a href="{{ route('ticket.view', $support->ticket) }}" class="icon-btn bg--primary approveBtn" data-bs-toggle="tooltip" data-bs-position="top" title="@lang('View')">
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
          {{ $supports->links() }}
      </div>

    </div>
</section>
@endsection
