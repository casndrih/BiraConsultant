@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-content-page">
    <div class="qj-content-wrap qj-content-wrap--narrow">
        <div>
            <!-- Breadcrumb -->
            <div class="qj-content-breadcrumb">
                <a href="{{ route('blog') }}">Blog</a>
                <span aria-hidden="true">/</span>
                <span>{{ $blog->title }}</span>
            </div>

            @include('partials.adsense', ['placement' => 'blog_detail'])

            <!-- Article -->
            <article class="qj-article-card">
                @if($blog->featured_image)
                    <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="qj-article-card__image">
                @endif
                <div class="qj-article-card__body">
                    @if($blog->category)
                        <span class="qj-blog-accent text-xs px-3 py-1 rounded-full">{{ $blog->category->name }}</span>
                    @endif
                    <h1 class="qj-article-title">{{ $blog->title }}</h1>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                        <span>By {{ $blog->author->name ?? 'Admin' }}</span>
                        <span>•</span>
                        <span>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="qj-article-prose">
                        {!! nl2br(e($blog->content)) !!}
                    </div>
                </div>
            </article>

            <!-- Related Posts -->
            @if($relatedBlogs->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-black dark:text-white mb-6">Related Articles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedBlogs as $related)
                            <article class="qj-story-card">
                                @if($related->featured_image)
                                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="qj-story-card__image">
                                @endif
                                <div class="qj-story-card__body">
                                    <h3 class="qj-story-card__title">
                                        <a href="{{ route('blog.detail', $related->slug) }}" class="qj-blog-link">{{ $related->title }}</a>
                                    </h3>
                                    <p class="qj-story-card__meta">{{ $related->created_at->format('M d, Y') }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
