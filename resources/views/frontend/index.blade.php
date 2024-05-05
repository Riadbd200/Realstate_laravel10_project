@extends('frontend.frontend_dashboard')

@section('main')

@section('title')
Real State Property Management
@endsection

        <!-- banner-section -->
        @include('frontend.banner_section')
        <!-- banner-section end -->

        <!-- category-section -->
        @include('frontend.category')
        <!-- category-section end -->

        <!-- feature-section -->
        @include('frontend.feature_section')
        <!-- feature-section end -->

        <!-- video-section -->
        @include('frontend.video_section')
        <!-- video-section end -->

        <!-- deals-section -->
        @include('frontend.deal_section')
        <!-- deals-section end -->

        <!-- testimonial-section end -->
        @include('frontend.testimonial')
        <!-- testimonial-section end -->

        <!-- chooseus-section -->
        @include('frontend.chooseus')
        <!-- chooseus-section end -->

        <!-- place-section -->
        @include('frontend.place')
        <!-- place-section end -->

        <!-- team-section -->
        @include('frontend.team')
        <!-- team-section end -->

        <!-- cta-section -->
        @include('frontend.cta')
        <!-- cta-section end -->

        <!-- news-section -->
        @include('frontend.news')
        <!-- news-section end -->

        <!-- download-section -->
        @include('frontend.download')
        <!-- download-section end -->

@endsection