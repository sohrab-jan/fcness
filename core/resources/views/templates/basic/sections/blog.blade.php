@php
    $blog = getContent('blog.content', true);

    if(request()->routeIs('home')){
        $blogs = getContent('blog.element', false, 3, false);
    }else{
        $blogs = App\Models\Frontend::where('data_keys', 'blog.element')->latest()->paginate(getPaginate());
    }

@endphp

<!-- blog section start -->
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="section-header text-center wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.3s">
            <div class="section-subtitle">{{ __(@$blog->data_values->heading) }}</div>
            <h2 class="section-title">{{ __(@$blog->data_values->sub_heading) }}</h2>
            </div>
        </div>
        </div><!-- row end -->
        <div class="row gy-4 justify-content-center">
        @foreach($blogs as $singleBlog)
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.5s">
                <div class="blog-post section--bg">
                    <div class="blog-post__thumb">
                        <img src="{{ getImage('assets/images/frontend/blog/' .@$singleBlog->data_values->image, '1920x1080') }}" alt="blog post">
                    </div>
                    <div class="blog-post__content">
                        <div class="blog-post__date fs--14px d-inline-flex align-items-center">
                            <i class="las la-calendar-alt fs--18px me-2"></i>
                            {{ showDateTime($singleBlog->created_at, 'd M') }}
                        </div>
                        <h4 class="blog-post__title">
                            <a href="{{ route('blog.details', ['slug'=>slug($singleBlog->data_values->title), 'id'=>$singleBlog->id]) }}">
                                {{ __($singleBlog->data_values->title) }}
                            </a>
                        </h4>
                        <a href="{{ route('blog.details', ['slug'=>slug($singleBlog->data_values->title), 'id'=>$singleBlog->id]) }}" class="text--base text-decoration-underline mt-3">
                            @lang('Read More')
                        </a>
                    </div>
                </div><!-- blog-post end -->
            </div>
        @endforeach
        </div>
    </div>
    <div class="justify-content-center d-flex mt-5">
        @if(!request()->routeIs('home'))
            {{ $blogs->links() }}
        @endif
    </div>
</section>
<!-- blog section end -->
