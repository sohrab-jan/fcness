@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="custom--card">
                    <div class="card-header">
                        <a href="{{route('ticket') }}" class="btn btn-sm btn--primary float-right">
                            @lang('My Support Ticket')
                        </a>
                    </div>

                    <div class="card-body">
                        <form class="transparent-form" action="{{route('ticket.store')}}"  method="post" enctype="multipart/form-data" onsubmit="return submitUserForm();">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name">@lang('Name')</label>
                                    <input type="text" name="name" value="{{@$user->firstname . ' '.@$user->lastname}}" class="form--control" placeholder="@lang('Enter your name')" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">@lang('Email address')</label>
                                    <input type="email"  name="email" value="{{@$user->email}}" class="form--control" placeholder="@lang('Enter your email')" readonly>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="website">@lang('Subject')</label>
                                    <input type="text" name="subject" value="{{old('subject')}}" class="form--control" placeholder="@lang('Subject')" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="priority">@lang('Priority')</label>
                                    <select name="priority" class="form--control">
                                        <option value="3">@lang('High')</option>
                                        <option value="2">@lang('Medium')</option>
                                        <option value="1">@lang('Low')</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="inputMessage">@lang('Message')</label>
                                    <textarea name="message" id="inputMessage" rows="6" class="form--control">{{old('message')}}</textarea>
                                </div>
                            </div>

                            <div class="row form-group ">
                                <div class="col-sm-9 file-upload">
                                    <label for="inputAttachments">@lang('Attachments')</label>

                                    <input type="file" name="attachments[]" id="inputAttachments" class="form-control mb-2" />

                                    <div id="fileUploadsContainer"></div>
                                    <label>
                                        @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                    </label>
                                </div>

                                <div class="col-sm-1 text-end mt-4">
                                    <button type="button" class="btn btn-md text-white bg--success mt-sm-2 addFile">
                                        <i class="las la-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="row form-group justify-content-center">
                                <div class="col-md-12">
                                    <button class="btn btn--base w-100 text-center" type="submit" id="recaptcha">@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.addFile').on('click',function(){
                $("#fileUploadsContainer").append(`
                    <div class="input-group">
                        <input type="file" name="attachments[]" class="form-control my-3" required/>
                        <div class="input-group-append support-input-group">
                            &nbsp;
                            <span class="input-group-text btn btn-md btn--danger remove-btn"><i class="las la-times"></i></span>
                        </div>
                    </div>
                `)
            });
            $(document).on('click','.remove-btn',function(){
                $(this).closest('.input-group').remove();
            });
        })(jQuery);
    </script>
@endpush
