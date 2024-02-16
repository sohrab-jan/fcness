@php
    $testimonial = getContent('testimonial.content', true);
    $testimonials = getContent('testimonial.element');
@endphp

<!-- testimonial section start -->
<section class="pt-100 pb-100 dark--overlay-two bg_img  bg-attachment-fixed overflow-hidden" style="background-image: url('{{ $activeTemplateTrue.'/images/bg/bg3.jpg' }}');">
    <div class="container">
        <div class="row justify-content-center gy-5">
        <div class="col-xl-4 col-lg-8 text-xl-start text-center wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.3s">
            <h2 class="section-title text-white">{{ __(@$testimonial->data_values->heading) }}</h2>
            <p class="mt-3 text-white-75">{{ __(@$testimonial->data_values->sub_heading) }}</p>
        </div>
        <div class="col-xl-8 ps-xl-5 wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.5s">
            <div class="testimonial-slider">
                @foreach($testimonials as $singleTestimonial)
                    <div class="single-slide">
                        <div class="testimonial-card">
                        <div class="testimonial-card__thumb">
                            <img src="{{ getImage('assets/images/frontend/testimonial/' .@$singleTestimonial->data_values->image, '1920x1080') }}" alt="image">
                        </div>
                        <div class="testimonial-card__content">
                            <p class="testimonial-card__description">
                                {{ __($singleTestimonial->data_values->say) }}
                            </p>
                            <h6 class="mt-3 text-white">{{ __($singleTestimonial->data_values->name) }}</h6>
                            <div class="ratings">
                            @for($i = 0; $i < $singleTestimonial->data_values->rating; $i++)
                                <i class="las la-star"></i>
                            @endfor
                            </div>
                        </div>
                        </div>
                    </div><!-- single-slide end -->
                @endforeach
            </div>
        </div>
        </div>
    </div>
</section>
<!-- testimonial section end -->
