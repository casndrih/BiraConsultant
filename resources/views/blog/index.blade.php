@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-content-page">
    <section class="qj-content-hero">
        <div class="qj-content-hero__inner">
            <span class="qj-content-hero__eyebrow">BiraConsultant Journal</span>
            <h1 class="qj-content-hero__title">Ideas for your next career move.</h1>
            <p class="qj-content-hero__subtitle">Career advice, hiring insights, and stories from across the Pacific.</p>
        </div>
    </section>

    <div class="qj-content-wrap qj-content-wrap--ad">
        @include('partials.adsense', ['placement' => 'blog'])
    </div>

    <div class="qj-content-wrap">

        <!-- Categories -->
        @if($categories->count() > 0)
            <div class="flex flex-wrap gap-2 mb-8">
                <a href="{{ route('blog') }}"
                   class="qj-category-chip {{ request('category') ? '' : 'is-active' }}">
                    All
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog', ['category' => $cat->id]) }}"
                       class="qj-category-chip {{ request('category') == $cat->id ? 'is-active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @if($blogs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($blogs as $blog)
                    <article class="qj-story-card">
                        @if($blog->featured_image)
                            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="qj-story-card__image">
                        @else
                            <div class="qj-story-card__placeholder">
                                <span>{{ substr($blog->title, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="qj-story-card__body">
                            @if($blog->category)
                                <span class="qj-blog-accent text-xs px-3 py-1 rounded-full">{{ $blog->category->name }}</span>
                            @endif
                            <h3 class="qj-story-card__title">
                                <a href="{{ route('blog.detail', $blog->slug) }}" class="qj-blog-link">{{ $blog->title }}</a>
                            </h3>
                            <p class="qj-story-card__excerpt">{{ Str::limit(strip_tags($blog->excerpt ?? $blog->content), 120) }}</p>
                            <div class="qj-story-card__meta">
                                <span>{{ $blog->author->name ?? 'Admin' }}</span>
                                <span>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="flex justify-center">
                {{ $blogs->links() }}
            </div>
        @else
            <div class="qj-content-panel text-center py-16">
                <p class="qj-content-copy">No blog posts yet. Check back soon!</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
