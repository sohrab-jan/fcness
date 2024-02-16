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
                        <th>@lang('Date')</th>
                        <th>@lang('Trx')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Post Balance')</th>
                        <th>@lang('Details')</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($logs as $data)
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

      <div class="mt-4 justify-content-center d-flex">
          {{ $logs->links() }}
      </div>

    </div>
</section>

@endsection

