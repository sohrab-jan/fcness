@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30">

    <div class="col-xl-12 col-lg-12 col-md-12 mb-30">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title border-bottom pb-2">@lang('Signal Details')</h5>

                <form action="{{ route('admin.signal.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $signal->id }}" required>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <label for="name">@lang('Name')</label>
                                        <input type="text" name="name" id="name" class="form-control" required value="{{ $signal->name }}">
                                    </div>

                                    <div class="form-group">
                                        <label  for="signal">@lang('Signal Details')</label>
                                        <textarea name="signal" id="signal" rows="6" required class="form-control">{{ __($signal->signal) }}</textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="useType">@lang('Set Time')</label>
                                                <select name="setTime" id="setTime" class="form-control setTime" required>
                                                    <option value="0" {{ $signal->send_signal_at == null ? 'selected' : null }}>@lang('Send Now')</option>
                                                    <option value="1" {{ $signal->send_signal_at != null ? 'selected' : null }}>@lang('Set Minute')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="minute">@lang('Send Signal After')</label>
                                                <div class="input-group">
                                                    <input type="number" min="1" name="minute" id="minute" class="form-control" required value="{{ $signal->minute }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">@lang('Minutes')</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="minute">@lang('Send Time')</label>
                                                @if($signal->send_signal_at != null)
                                                    <input type="text" class="form-control" value="{{ showDateTime($signal->send_signal_at) }}" readonly>
                                                @else
                                                <br>
                                                    @lang('N/A')
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">@lang('Status')</label>
                                        <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" id="status" data-on="@lang('Enable')" data-off="@lang('Disable')" name="status" @if($signal->status == 1) checked @endif>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <h5 class="card-title border-bottom pb-2">@lang('Selected Package For This Signal')</h5>
                            <ol>
                                @foreach($packages as $package)
                                    <li>
                                        <input type="checkbox" name="plan[]" class="form--control" value="{{ $package->id }}" id="{{ $package->id }}"
                                        @if(in_array($package->id, json_decode($signal->plan_id, true)))
                                            checked
                                        @endif
                                        >
                                        <label for="{{ $package->id }}">
                                            {{ __($package->name) }}
                                        </label>
                                    </li>
                                @endforeach
                            </ol>
                            <br/>
                            <h5 class="card-title border-bottom pb-2">@lang('Send Via')</h5>
                            <ol>
                                @foreach($send_via as $via)
                                    <li>
                                        <input type="checkbox" name="via[]" class="form--control" value="{{ $via }}" id="{{ $via }}"
                                        @if(in_array($via, json_decode($signal->send_via, true)))
                                            checked
                                        @endif
                                        >
                                        <label for="{{ $via }}">
                                            {{ __($via) }}
                                        </label>
                                    </li>
                                @endforeach
                            </ol>
                        </div>

                    </div>

                    @if($signal->send == 0)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Update')
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.signal') }}" class="btn btn-sm btn--primary box--shadow1 text--small">
        <i class="las la-angle-double-left"></i>
        @lang('Go To Back')
    </a>
@endpush

@push('script')
<script>
    (function ($) {
    "use strict";

        if(@json($signal->send_signal_at) == null){
            $('#minute').attr('disabled', 'disabled');
        }

        $('.setTime').on('change', function () {
            var selected =  $(".setTime option:selected").val();
            if(selected == 0){
                $('#minute').attr('disabled', 'disabled');
            }else{
                $('#minute').removeAttr('disabled');
            }
        });

        if( @json($signal->send) == 1 ){
            $('input').attr('disabled', 'disabled');
            $('textarea').attr('disabled', 'disabled');
        }

    })(jQuery);
</script>
@endpush
