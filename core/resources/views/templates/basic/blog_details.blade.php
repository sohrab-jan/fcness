@extends($activeTemplate.'layouts.frontend')

@section('content')
    <!-- blog details section start -->
    <section class="pt-100 pb-100 content-section">
        <div class="container">
          <div class="row gy-5">
            <div class="col-lg-8">
              <div class="blog-post__date fs--14px d-inline-flex align-items-center"><i class="las la-calendar-alt fs--18px me-2"></i> 24 March, 2021</div>
              <h2 class="blog-details-title mb-3">
                  {{ __($blog->data_values->title) }}
              </h2>
              <div class="blog-details-thumb">
                <img src="{{ getImage('assets/images/frontend/blog/' .@$blog->data_values->image, '1920x1080') }}" alt="image" class="rounded-3">
              </div>
              <div class="blog-details-content mt-4">
                <p class="fs--18px">
                    @php
                        echo $blog->data_values->description_nic;
                    @endphp
                </p>
              </div>
              <ul class="post-share d-flex flex-wrap align-items-center justify-content-center mt-5">
                <li class="caption">@lang('Share') : </li>
                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Facebook">
                    <a href="https://www.facebook.com/sharer/sharer.php?=u{{ url()->current() }}" target="_blank">
                        <i class="lab la-facebook-f"></i>
                    </a>
                </li>
                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Linkedin">
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}" target="_blank">
                        <i class="lab la-linkedin-in"></i>
                    </a>
                </li>
                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Twitter">
                    <a href="https://twitter.com/home?status={{ url()->current() }}" target="_blank">
                        <i class="lab la-twitter"></i>
                    </a>
                </li>
                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Instagram">
                    <a href="http://www.reddit.com/submit?url={{ url()->current() }}" target="_blank">
                        <i class="lab la-reddit"></i>
                    </a>
                </li>
              </ul>
            </div>
            <div class="col-lg-4 ps-xl-5">
              <div class="blog-sidebar rounded-3 section--bg">
                <h4 class="title">@lang('Latest Posts')</h4>
                <ul class="s-post-list">
                @foreach($latestBlogs as $singleBlog)
                  <li class="s-post d-flex flex-wrap">
                    <div class="s-post__thumb">
                      <img src="{{ getImage('assets/images/frontend/blog/' .@$singleBlog->data_values->image, '1920x1080') }}" alt="image">
                    </div>
                    <div class="s-post__content">
                      <h6 class="s-post__title">
                        <a href="{{ route('blog.details', ['slug'=>slug($singleBlog->data_values->title), 'id'=>$singleBlog->id]) }}">
                            {{ __($singleBlog->data_values->title) }}
                        </a>
                    </h6>
                      <p class="fs--12px mt-2"><i class="las la-calendar-alt fs--14px me-1"></i>{{ showDateTime($singleBlog->created_at, 'd M') }}</p>
                    </div>
                  </li>
                @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- blog details section end -->
@endsection

@push('fbComment')
	@php echo loadFbComment() @endphp
@endpush

@push('share')
	{{--<!-- Google / Search Engine Tags -->--}}
    <meta itemprop="name" content="Blog Details">
    <meta itemprop="description" content="{{ @$blog->data_values->title }}">
    <meta itemprop="image" content="{{ getImage('assets/images/frontend/blog/' .@$blog->data_values->image, '1920x1080') }}">

    {{--<!-- Facebook Meta Tags -->--}}
    <meta property="og:title" content="Blog Details">
    <meta property="og:description" content="{{ @$blog->data_values->title }}">
    <meta property="og:image" content="{{ getImage('assets/images/frontend/blog/' .@$blog->data_values->image, '1920x1080') }}"/>
@endpush

