@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Send Status')</th>
                                <th>@lang('Send Time')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($signals as $data)
                            <tr>
                                <td data-label="@lang('Name')">
                                    <span class="font-weight-bold">
                                        {{ shortDescription($data->name, 60) }}
                                    </span>
                                </td>

                                <td data-label="@lang('Send Status')">
                                    @if($data->send == 1)
                                        <span class="badge badge--success">
                                            @lang('Send')
                                        </span>
                                    @else
                                        <span class="badge badge--warning">
                                            @lang('Not Send')
                                        </span>
                                    @endif
                                </td>

                                <td data-label="@lang('Send Time')">
                                    @if($data->send_signal_at == null)
                                        @lang('N/A')
                                    @else
                                        {{ showDateTime($data->send_signal_at) }}
                                    @endif
                                </td>

                                <td data-label="@lang('Status')">
                                    @if($data->status == 1)
                                        <span class="badge badge--success">
                                            @lang('Enable')
                                        </span>
                                    @else
                                        <span class="badge badge--warning">
                                            @lang('Disable')
                                        </span>
                                    @endif
                                </td>

                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.signal.update.page', $data->id) }}" class="icon-btn editBtn" data-toggle="tooltip"
                                        title="{{ $data->send == 0 ? 'Edit' : 'View' }}"
                                        data-original-title="@lang('Edit')"
                                    >
                                        <i class="las la-edit text--shadow"></i>
                                    </a>

                                    @if($data->send == 0)
                                        <a href="javascript:void(0)" class="icon-btn deleteBtn bg--danger ml-1" data-toggle="tooltip" title="@lang('Delete')" data-original-title="@lang('Delete')"
                                            data-id="{{ $data->id }}"
                                        >
                                            <i class="las la-trash text--shadow"></i>
                                        </a>
                                    @endif

                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">@lang('Data Not Found')!</td>
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

    {{-- DELETE METHOD MODAL --}}
    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation')!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.signal.delete') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" required>
                        <p class="font-weight-bold text-center">@lang('Are you sure to delete this signal')?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Delete')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ADD METHOD MODAL --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Signal')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.signal.add') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-lg-12 form-group">
                                <label for="name">@lang('Name')</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="signal">@lang('Signal Details')</label>
                                <textarea name="signal" id="signal" rows="6" required class="form-control"></textarea>
                            </div>

                            <div class="col-lg-4 form-group">
                               <label for="setTime">@lang('Set Time')</label>
                               <select name="setTime" id="setTime" class="form-control setTime" required>
                                   <option value="">@lang('Select One')</option>
                                   <option value="0">@lang('Send Now')</option>
                                   <option value="1">@lang('Set Minute')</option>
                               </select>
                            </div>

                            <div class="col-lg-4 form-group">
                                <label for="minute">@lang('Send Signal After')</label>
                                <div class="input-group">
                                    <input type="number" min="1" name="minute" id="minute" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">@lang('Minutes')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 form-group">
                                <label for="via">@lang('Send Via')</label>
                                <select name="via[]" id="via" class="form-control select2-auto-tokenize" multiple>
                                    <option value="Email">@lang('Email')</option>
                                    <option value="SMS">@lang('SMS')</option>
                                    <option value="Telegram">@lang('Telegram')</option>
                                </select>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="plan">@lang('Select Package For Signal')</label>
                                <select name="plan[]" id="plan" class="form-control select2-auto-tokenize" multiple>
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}">{{ __($package->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="status">@lang('Status')</label>
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" id="status" data-on="@lang('Enable')" data-off="@lang('Disable')" name="status">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-sm btn--primary box--shadow1 text--small addNew" type="submit">
        <i class="las la-plus"></i>
        @lang('Add New')
    </button>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.addNew').on('click', function () {
                var modal = $('#addModal');
                modal.find('.method-name').text($(this).data('name'));
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.deleteBtn').on('click', function () {
                var modal = $('#deleteModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.setTime').on('change', function () {

                var selected =  $(".setTime option:selected").val();

                if(selected == 0){
                    $('#minute').attr('disabled', 'disabled');
                }else{
                    $('#minute').removeAttr('disabled');
                }

            });

        })(jQuery);
    </script>
@endpush
