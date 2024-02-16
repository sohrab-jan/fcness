<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $general->sitename(__($pageTitle)) }}</title>
  @include('partials.seo')
  <!-- bootstrap 5  -->
  <link rel="stylesheet" href="{{asset('assets/global/css/bootstrap.min.css')}}">
  <!-- fontawesome 5  -->
  <link rel="stylesheet" href="{{asset('assets/global/css/all.min.css')}}">
  <!-- lineawesome font -->
  <link rel="stylesheet" href="{{asset('assets/global/css/line-awesome.min.css')}}">
  <!-- lightcase css -->
  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/lightcase.css')}}">
  <!-- slick slider css -->
  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/lib/slick.css')}}">
  <!-- main css -->
  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/main.css')}}">

  <link rel="stylesheet" href="{{ asset($activeTemplateTrue. 'css/color.php?color='.$general->base_color.'&secondColor='.$general->secondary_color) }}">

  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/custom.css')}}">

  @stack('style-lib')

  @stack('style')

</head>
  <body>
    @stack('fbComment')
    <progress max="100" value="0" class="page-scroll-bar"></progress>

    <!-- scroll-to-top start -->
    <div class="scroll-to-top">
      <span class="scroll-icon">
        <i class="las la-arrow-up"></i>
      </span>
    </div>
    <!-- scroll-to-top end -->

    <!-- preloader start -->
    <div class="preloader">
      <div class="preloader__inner">
        <div class="preloader__box">
          <span class="line-1"></span>
          <span class="line-2"></span>
          <span class="line-3"></span>
        </div>
        <h4 class="preloader__sitename text--base">{{ $general->sitename }}</h4>
      </div>
    </div>
    <!-- preloader end -->

    @include($activeTemplate.'partials.header')

  <div class="main-wrapper">

    @if(request()->routeIs('home'))

    @php
        $banner = getContent('banner.content', true);
    @endphp

    <!-- hero section start -->
    <section class="hero bg_img" style="background-image: url('{{ getImage('assets/images/frontend/banner/' .@$banner->data_values->image, '350x420') }}');">
      <div class="hero__radar">
        <div class="circle"><img src="{{asset($activeTemplateTrue.'images/elements/hero/circle.png')}}" alt="image"></div>
        <div class="radar"><img src="{{asset($activeTemplateTrue.'images/elements/hero/radar.png')}}" alt="image"></div>
        <span class="dot-1"></span>
        <span class="dot-2"></span>
        <span class="dot-3"></span>
        <span class="dot-4"></span>
        <span class="dot-5"></span>
        <span class="dot-6"></span>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-lg-6 text-lg-start text-center">
            <div class="hero__top-title wow fadeInUp text--base" data-wow-duration="0.5" data-wow-delay="0.3s">
                {{ __(@$banner->data_values->title) }}
            </div>
            <h2 class="hero__title text-white wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.5s">
                {{ __(@$banner->data_values->heading) }}
            </h2>
            <p class="hero__description text-white wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.7s">
                {{ __(@$banner->data_values->sub_heading) }}
            </p>
            <a href="{{ @$banner->data_values->button_url }}" class="btn btn--base mt-4 wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.7s">
                {{ __(@$banner->data_values->button_text) }}
            </a>
          </div>
        </div>
      </div>
    </section>
    <!-- hero section end -->
    @else

    @php
        $breadCrumb = getContent('breadCrumb.content', true);
    @endphp

    <!-- inner hero section start -->
    <section class="inner-hero bg_img" style="background-image: url('{{ getImage('assets/images/frontend/breadCrumb/' .@$breadCrumb->data_values->image, '1920x510') }}');">
        <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
            <h2 class="title text-white">{{ __($pageTitle) }}</h2>
            <ul class="page-breadcrumb justify-content-center">
                <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                <li>{{ __($pageTitle) }}</li>
            </ul>
            </div>
        </div>
        </div>
    </section>
    <!-- inner hero section end -->
    @endif

    @yield('content')

    </div><!-- main-wrapper end -->

    @php
        $cookie = App\Models\Frontend::where('data_keys','cookie.data')->first();
    @endphp

    @if(@$cookie->data_values->status && !session('cookie_accepted'))
      <div class="cookie-modal show" id="cookieModal">
          <div class="container">
              <div class="cookie-header mb-1">
                  <h5 class="text--base">@lang('Cookie Policy')</h5>
              </div>
              <p class="mb-2 d-inline">
                  @php echo @$cookie->data_values->description @endphp
              </p>

              <a class="btn btn-sm btn--primary ms-3" href="{{ @$cookie->data_values->link }}" target="_blank">@lang('Learn More')</a>
              <a href="{{ route('cookie.accept') }}" class="btn btn-sm btn--success">@lang('Accept')</a>
          </div>
      </div>
    @endif

    

    @include($activeTemplate.'partials.footer')

  <!-- jQuery library -->
  <script src="{{asset('assets/global/js/jquery-3.6.0.min.js')}}"></script>
  <!-- bootstrap js -->

  @stack('script-lib')

  <script src="{{asset($activeTemplateTrue.'js/lib/bootstrap.bundle.min.js')}}"></script>
  <!-- slick  slider js -->
  <script src="{{asset($activeTemplateTrue.'js/lib/slick.min.js')}}"></script>
  <!-- wow js  -->
  <script src="{{asset($activeTemplateTrue.'js/lib/wow.min.js')}}"></script>
  <!-- wow js  -->
  <script src="{{asset($activeTemplateTrue.'js/lib/lightcase.js')}}"></script>

  <script src='{{asset($activeTemplateTrue.'js/gsap.min.js')}}/'></script>
  <script src='{{asset($activeTemplateTrue.'js/ScrollTrigger.js')}}'></script>

  @include('partials.plugins')
  @include('partials.notify')

  <!-- main js -->
  <script src="{{asset($activeTemplateTrue.'js/app.js')}}"></script>

    <script>
        $(document).ready(function(){
            "use strict";

            $(".langSel").on("change", function() {
                window.location.href = "{{route('home')}}/change/"+$(this).val() ;
            });
            
            let darkMode = @json($general->dark_template);

            if (darkMode != 1) {
                document.body.classList.add('lightmode');
                localStorage.setItem('darkMode', 'enabled');
            }else{
                document.body.classList.remove('lightmode');
                localStorage.setItem('darkMode', null);
            }

            let navLink = $('.main-menu li a');
            let currentRoute = '{{ url()->current() }}';

            $.each(navLink, function(index, value) {
                if(value.href == currentRoute){
                    let li = value.closest('li');
                    $(li).addClass('active');
                }
            });

        });
    </script>

    @stack('script')

  </body>
</html>



