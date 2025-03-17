@extends('frontend.layouts.master')

@section('title','E-TECH || Blog Detail Page')

@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Trang chủ<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">Thanh Blog</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Start Blog Single -->
    <section class="blog-single section">
        <div class="container">
            <div class="row">
                <!-- Blog Content -->
                <div class="col-lg-8 col-12">
                    <div class="blog-single-main">
                        <div class="row">
                            <div class="col-12">
                                <!-- Blog Image -->
                                <div class="image">
                                    <img src="{{ $post->photo }}" alt="{{ $post->title }}">
                                </div>
                                <!-- Blog Details -->
                                <div class="blog-detail">
                                    <h2 class="blog-title">{{ $post->title }}</h2>
                                    <div class="content">
                                        @if($post->quote)
                                            <blockquote><i class="fa fa-quote-left"></i> {!! $post->quote !!}</blockquote>
                                        @endif
                                        <p>{!! $post->description !!}</p>
                                    </div>
                                </div>
                                <!-- Blog Tags -->
                                <div class="share-social">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="content-tags">
                                                <h4>Tags:</h4>
                                                <ul class="tag-inner">
                                                    @php
                                                        $tags = explode(',', $post->tags);
                                                    @endphp
                                                    @foreach($tags as $tag)
                                                        <li><a href="javascript:void(0);">{{ $tag }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                         
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4 col-12">
                    <div class="main-sidebar">
                        <!-- Search Widget -->
                        <div class="single-widget search">
                            <form class="form" method="GET" action="{{ route('blog.search') }}">
                                <input type="text" placeholder="Search Here..." name="search">
                                <button class="button" type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                        <!-- Categories Widget -->
                        <div class="single-widget category">
                            <h3 class="title">Danh mục blog</h3>
                            <ul class="categor-list">
                                @foreach(Helper::postCategoryList('posts') as $cat)
                                    <li><a href="#">{{ $cat->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Recent Posts Widget -->
                        <div class="single-widget recent-post">
                            <h3 class="title">Bài viết gần đây</h3>
                            @foreach($recent_posts as $recent)
                                <div class="single-post">
                                    <div class="image">
                                        <img src="{{ $recent->photo }}" alt="{{ $recent->title }}">
                                    </div>
                                    <div class="content">
                                        <h5><a href="#">{{ $recent->title }}</a></h5>
                                        <ul class="comment">
                                            <li><i class="fa fa-calendar" aria-hidden="true"></i>{{ $recent->created_at->format('d M, Y') }}</li>
                                            <li><i class="fa fa-user" aria-hidden="true"></i>{{ $recent->author_info->name ?? 'Anonymous' }}</li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Tags Widget -->
                        <div class="single-widget side-tags">
                            <ul class="tag">
                            @php
            $tags = Helper::postTagList('posts');
        @endphp

        @if(!empty($tags) && is_iterable($tags))
            @foreach($tags as $tag)
                <li><a href="#">{{ $tag->title }}</a></li>
            @endforeach
        @else
            <li>No tags available</li>
        @endif
                            </ul>
                        </div>
                        <!-- Newsletter Widget -->
                        <div class="single-widget newsletter">
                            <h3 class="title">Bản tin</h3>
                            <div class="letter-inner">
                                <h4>Đăng ký và nhận tin tức
                                 <br> Cập nhật mới nhất.</h4>
                                <form action="{{ route('subscribe') }}" method="POST">
                                    @csrf
                                    <div class="form-inner">
                                        <input type="email" name="email" placeholder="Enter your email">
                                        <button type="submit" class="btn mt-2">Gửi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Blog Single -->
@endsection

@push('styles')
    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5f2e5abf393162001291e431&product=inline-share-buttons' async='async'></script>
@endpush
