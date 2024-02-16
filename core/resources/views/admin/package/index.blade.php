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
                                <th>@lang('Name')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Validity')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($packages as $data)
                            <tr>

                                <td data-label="@lang('Name')">
                                    <span class="font-weight-bold">
                                        {{ __($data->name) }}
                                    </span>
                                </td>

                                <td data-label="@lang('Price')">
                                    <span class="font-weight-bold">
                                        {{ showAmount($data->price, 2) }}
                                        {{ __($general->cur_text) }}
                                    </span>
                                </td>

                                <td data-label="@lang('Validity')">
                                    {{ $data->validity }} @lang('Days')
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
                                    <a href="#" class="icon-btn editBtn" data-toggle="tooltip" title="" data-original-title="@lang('Edit')"
                                    data-name="{{ $data->name }}"
                                    data-id="{{ $data->id }}"
                                    data-status="{{ $data->status }}"
                                    data-features="{{ $data->features }}"
                                    data-price="{{ getAmount($data->price, 2) }}"
                                    data-validity="{{ $data->validity }}"
                                    >
                                        <i class="las la-edit text--shadow"></i>
                                    </a>
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
                    {{ paginateLinks($packages) }}
                </div>
            </div>
        </div>
    </div>

    {{-- ADD METHOD MODAL --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Package')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.package.add') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-lg-12 form-group">
                                <label for="name">@lang('Name')</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="price">@lang('Price')</label>
                                <div class="input-group">
                                    <input type="text" name="price" id="price" class="form-control" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">{{ __($general->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="validity">@lang('Validity')</label>
                                <div class="input-group">
                                    <input type="number" min="1" name="validity" id="validity" class="form-control" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">@lang('Days')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="features">@lang('Features')</label>
                                <select name="features[]" id="features" class="form-control select2-auto-tokenize" multiple>
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

    {{-- EDIT METHOD MODAL --}}
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit Package')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.package.update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id" required>
                            <div class="col-lg-12 form-group">
                                <label for="editName">@lang('Name')</label>
                                <input type="text" name="name" id="editName" class="form-control" required>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="editPrice">@lang('Price')</label>
                                <div class="input-group">
                                    <input type="text" name="price" id="editPrice" class="form-control" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">{{ __($general->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="editValidity">@lang('Validity')</label>
                                <div class="input-group">
                                    <input type="number" min="1" name="validity" id="editValidity" class="form-control" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">@lang('Days')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="editFeatures">@lang('Features')</label>
                                <select name="features[]" id="editFeatures" class="form-control select2-auto-tokenize" multiple>
                                </select>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="editStatus">@lang('Status')</label>
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" id="editStatus" data-on="@lang('Enable')" data-off="@lang('Disable')" name="status">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Update')</button>
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

            $('.editBtn').on('click', function () {
                var modal = $('#editModal');

                modal.find('input[name=name]').val($(this).data('name'));
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('input[name=price]').val($(this).data('price'));
                modal.find('input[name=validity]').val($(this).data('validity'));

                var features = $(this).data('features');
                var select = $('#editFeatures');
                select.empty();

                for(let i = 0; i < features.length; i++) {
                        select.append(
                            $(`<option selected>`).val(features[i]).text(features[i])
                    )
                }

                if($(this).data('status') == 1){
                    $('#editStatus').parent('div').removeClass('off');
                    $('#editStatus').prop('checked', true);
                }else{
                    $('#editStatus').parent('div').addClass('off');
                    $('#editStatus').prop('checked', false);
                }

                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
