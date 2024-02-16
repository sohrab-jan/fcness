@extends($extends)

@section('content')
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="custom--card">
                    <div class="card-header card-header-bg d-flex flex-wrap justify-content-between align-items-center">
                        <h5 class="card-title mt-0">
                            @if($my_ticket->status == 0)
                                <span class="badge badge--success py-2 px-3">@lang('Open')</span>
                            @elseif($my_ticket->status == 1)
                                <span class="badge badge--primary py-2 px-3">@lang('Answered')</span>
                            @elseif($my_ticket->status == 2)
                                <span class="badge badge--warning py-2 px-3">@lang('Replied')</span>
                            @elseif($my_ticket->status == 3)
                                <span class="badge bg--dark py-2 px-3">@lang('Closed')</span>
                            @endif
                            <span class="text-white">[@lang('Ticket')#{{ $my_ticket->ticket }}] {{ $my_ticket->subject }}</span>
                        </h5>
                        @if($my_ticket->status != 3)
                        <button class="btn btn-sm btn-danger close-button" type="button" title="@lang('Close Ticket')" data-bs-toggle="modal" data-bs-target="#DelModal"><i class="las la-times"></i>
                        </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($my_ticket->status != 4)
                            <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}" enctype="multipart/form-data" class="transparent-form">
                                @csrf
                                <input type="hidden" name="replayTicket" value="1">
                                <div class="row justify-content-between">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea name="message" class="form--control" id="inputMessage" placeholder="@lang('Your Reply')" rows="4" cols="10"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-md-8">
                                        <div class="row justify-content-between">
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    <label for="inputAttachments">@lang('Attachments')</label>
                                                    <input type="file" name="attachments[]" id="inputAttachments" class="form-control"/>
                                                    <div id="fileUploadsContainer"></div>
                                                    <label>
                                                        @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group pt-2">
                                                    <a href="javascript:void(0)" class="btn btn-md btn--success btn-round mt-md-4 addFile">
                                                        <i class="las la-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 pt-2">
                                        <button type="submit" class="btn btn--success custom-success mt-md-4 w-100">
                                            <i class="fa fa-reply"></i> @lang('Reply')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($messages as $message)
                                    @if($message->admin_id == 0)
                                        <div class="row support-answer-wrapper radius-3 my-3 py-3 mx-2">
                                            <div class="col-md-3 border-right text-end">
                                                <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                            </div>
                                            <div class="col-md-9 ps-lg-4">
                                                <p class="text-muted font-weight-bold my-3">
                                                    @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                                <p>{{$message->message}}</p>
                                                @if($message->attachments()->count() > 0)
                                                    <div class="mt-2">
                                                        @foreach($message->attachments as $k=> $image)
                                                            <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="row support-answer-wrapper support-answer-wrapper-admin my-3 py-3 mx-2">
                                            <div class="col-md-3 border-right text-right">
                                                <h5 class="my-3">{{ $message->admin->name }}</h5>
                                                <p class="lead text-muted">@lang('Staff')</p>
                                            </div>
                                            <div class="col-md-9">
                                                <p class="text-muted font-weight-bold my-3">
                                                    @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                                <p>{{$message->message}}</p>
                                                @if($message->attachments()->count() > 0)
                                                    <div class="mt-2">
                                                        @foreach($message->attachments as $k=> $image)
                                                            <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}">
                @csrf
                <input type="hidden" name="replayTicket" value="2">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Confirmation')!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <strong>@lang('Are you sure you want to close this support ticket')?</strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success btn-sm">@lang("Confirm")</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.delete-message').on('click', function (e) {
                $('.message_id').val($(this).data('id'));
            });
            $('.addFile').on('click',function(){
                $("#fileUploadsContainer").append(
                    `<div class="input-group mt-2">
                        <input type="file" name="attachments[]" class="form-control" required />
                        <div class="input-group-append support-input-group mt-0">
                            &nbsp;
                            <span class="input-group-text btn btn-md btn--danger remove-btn"><i class="las la-times"></i></span>
                        </div>
                    </div>`
                )
            });
            $(document).on('click','.remove-btn',function(){
                $(this).closest('.input-group').remove();
            });
        })(jQuery);

    </script>
@endpush
