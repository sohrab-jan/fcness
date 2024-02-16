@php
    $package = getContent('package.content', true);

    if( request()->routeIs('home') ){
        $packages = App\Models\Package::where('status', 1)->take(3)->get();
    }else{
        $packages = App\Models\Package::where('status', 1)->paginate(getPaginate());
    }

@endphp

<!-- packaage section start -->
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="section-header text-center wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.3s">
                <div class="section-subtitle">{{ __(@$package->data_values->heading) }}</div>
                <h2 class="section-title">{{ __(@$package->data_values->sub_heading) }}</h2>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row gy-4 justify-content-center">
            <div class="col-lg-10">
                <div class="row gy-4 justify-content-center">
                    @foreach($packages as $singlePackage)
                        <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.5s">
                            <div class="package-card">
                                {{-- <div class="package-card popular-package" data-text="Eta Sera"> --}}
                                <h4 class="package-card__name">{{ __($singlePackage->name) }}</h4>
                                <div class="package-card__price">{{ $general->cur_sym }}{{ showAmount($singlePackage->price, 0) }}</div>
                                <ul class="package-card__feature-list mt-4">
                                    @foreach(json_decode($singlePackage->features, true) as $feature)
                                        <li>{{ __($feature) }}</li>
                                    @endforeach
                                </ul>
                                <div class="mt-4">
                                    <a href="javascript:void(0)" class="btn btn-outline--base chooseBtn"
                                    @auth
                                        data-name="{{$singlePackage->name}}"
                                        data-id="{{$singlePackage->id}}"
                                        data-price="{{ getAmount($singlePackage->price, 2) }}"
                                        data-validity="{{ $singlePackage->validity }}"
                                    @endauth
                                    >
                                        @lang('Choose Plan')
                                    </a>
                                </div>
                                </div><!-- package-card end -->
                            </div>
                        @endforeach
                    </div><!-- row end -->
                </div>

                @if(!request()->routeIs('home'))
                    <div class="mt-5 d-flex text-center justify-content-center">
                        {{ $packages->links() }}
                    </div>
                @endif

            </div><!-- row end -->
        </div>
    </div>
</section>
<!-- packaage section end -->

@auth
<div class="modal fade cmn--modal" id="chooseModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="modal-title method-name"></strong>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('user.buy.package')}}" method="post">
                @csrf
                <div class="modal-body pt-0">
                    <div class="form-group">
                        <input type="hidden" name="id" required>
                    </div>
                    <ul class="list-group">
                        <li>@lang('Package') : <span class="packageName fw-bold"></span></li>
                        <li>@lang('Price') : <span class="packagePrice fw-bold"></span></li>
                        <li>@lang('Validity') : <span class="packageValidity fw-bold"></span></li>
                        <li>@lang('Your Balance') : <span class="fw-bold">{{ showAmount(Auth::user()->balance, 2) }} {{ __($general->cur_text) }} </span></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <div class="prevent-double-click">
                        <button type="submit" class="btn btn-sm btn--success">@lang('Confirm')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@else
<div class="modal fade cmn--modal" id="chooseModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="modal-title method-name text-dark">@lang('Please login before buy a package')</strong>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="form-group mt-4">
                    <a href="{{ route('user.login') }}" class="btn btn-sm btn--success w-100">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endauth

@push('script')
<script>

    (function ($) {
        "use strict";

        $('.chooseBtn').on('click', function () {
            var modal = $('#chooseModal');

            if(@json( Auth::user() )){
                modal.find('.modal-title').text('Are you sure to buy '+$(this).data('name'));
                modal.find('input[name=id]').val($(this).data('id'));

                modal.find('.packageName').text($(this).data('name'));
                modal.find('.packagePrice').text($(this).data('price')+' '+@json( __($general->cur_text) ));
                modal.find('.packageValidity').text($(this).data('validity')+' Days');
            }

            modal.modal('show');
        });

    })(jQuery);

</script>
@endpush
