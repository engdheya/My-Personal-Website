@extends('layouts.app')

@section('title', $post->seo_title ?: (loc_field($post, 'title').' | '.site_name()))
@section('meta_description', $post->meta_description ?: loc_field($post, 'excerpt'))
@section('canonical', $post->canonical_url ?: url('blog/'.$post->slug))
@section('og_image', absolute_url($post->og_image ?: $post->featured_image, '/images/og-cover.png'))
@section('og_type', 'article')
@section('keywords', $post->focus_keyword ?: '')

@section('content')
@php
    $shareUrl = urlencode($post->canonical_url ?: url('blog/'.$post->slug));
    $shareTitle = urlencode($post->title);
@endphp
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  @include('partials.breadcrumbs')

  <!-- Article Header -->
  <header class="mb-10 space-y-4">
    <div class="flex flex-wrap items-center gap-3">
      @if ($post->category)
      <a href="{{ url('blog').'?category='.$post->category->slug.(app()->getLocale() === 'en' ? '&lang=en' : '') }}" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 hover:bg-blue-200 transition-colors">
        {{ loc_field($post->category, 'name') }}
      </a>
      @endif
      <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">
        {{ __('site.blog_published_on') }}: {{ jdate($post->published_at) }}
      </span>
      @if ($post->updated_at && $post->published_at && $post->updated_at->ne($post->published_at))
      <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">
        • {{ __('site.blog_updated_on') }}: {{ jdate($post->updated_at) }}
      </span>
      @endif
      <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">
        • {{ $post->reading_time ?: 5 }} {{ __('site.blog_reading_time') }}
      </span>
    </div>

    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
      {{ loc_field($post, 'title') }}
    </h1>

    <p class="text-lg text-slate-600 dark:text-slate-300 leading-relaxed">
      {{ loc_field($post, 'excerpt') }}
    </p>

    <!-- Author & Social Share Bar -->
    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div class="flex items-center gap-3">
        <img src="{{ url_path(setting('profile_image'), '/images/dheya-avatar.jpg') }}" alt="{{ setting('author_name') ?: 'Dheya Abbas' }}" class="w-10 h-10 rounded-full object-cover border border-slate-300 dark:border-slate-700">
        <div class="text-xs">
          <div class="font-bold text-slate-900 dark:text-white">
            {{ author_name() }}
          </div>
          <div class="text-slate-500">{{ author_title() }}</div>
        </div>
      </div>

      <!-- Social Share Buttons -->
      <div class="flex items-center gap-2 text-xs font-semibold">
        <span class="text-slate-400">{{ __('site.blog_share') }}:</span>
        <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-sky-50 dark:hover:bg-sky-950 text-slate-600 dark:text-slate-300 hover:text-sky-500 transition-colors" title="Share on X">
          𝕏
        </a>
        <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-sky-50 dark:hover:bg-sky-950 text-slate-600 dark:text-slate-300 hover:text-sky-500 transition-colors" title="Share on Telegram">
          ✈️
        </a>
        <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-emerald-50 dark:hover:bg-emerald-950 text-slate-600 dark:text-slate-300 hover:text-emerald-500 transition-colors" title="Share on WhatsApp">
          💬
        </a>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950 text-slate-600 dark:text-slate-300 hover:text-blue-500 transition-colors" title="Share on LinkedIn">
          in
        </a>
      </div>
    </div>
  </header>

  <!-- Featured Image -->
  <div class="rounded-3xl overflow-hidden shadow-2xl bg-slate-900 border border-slate-200 dark:border-slate-800 mb-12 aspect-video">
    <img
      src="{{ url_path($post->featured_image, '/images/posts/laravel-mysql-driver.jpg') }}"
      alt="{{ $post->alt_text ?: $post->title }}"
      class="w-full h-full object-cover"
      loading="eager"
    />
  </div>

  <!-- Table of Contents -->
  @if (! empty($toc))
    <nav class="my-8 p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm" aria-label="Table of Contents">
      <div class="flex items-center gap-2 mb-4 text-slate-900 dark:text-white font-bold text-base">
        <span>📑</span>
        <span>{{ __('site.blog_toc') }}</span>
      </div>
      <ol class="space-y-2 text-sm">
        @foreach ($toc as $item)
          <li class="{{ $item['level'] === 3 ? 'ps-6 text-xs' : 'font-semibold' }}">
            <a href="#{{ $item['id'] }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors hover:underline">
              {{ $item['text'] }}
            </a>
          </li>
        @endforeach
      </ol>
    </nav>
  @endif

  <!-- Article In-Content Ad -->
  @include('partials.ads', ['position' => 'article'])

  <!-- Rendered Markdown Content -->
  <div class="prose dark:prose-invert max-w-none text-slate-800 dark:text-slate-200 leading-relaxed text-base">
    {!! $contentHtml !!}
  </div>

  <!-- Tags Bar -->
  @if ($postTags->isNotEmpty())
    <div class="my-8 pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-wrap items-center gap-2">
      <span class="text-xs text-slate-400 font-mono">{{ __('site.blog_tags') }}:</span>
      @foreach ($postTags as $tag)
        <a href="{{ url('search').'?q='.urlencode($tag->name).(app()->getLocale() === 'en' ? '&lang=en' : '') }}" class="px-3 py-1 rounded-lg text-xs font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-600 hover:text-white transition-colors">
          #{{ $tag->name }}
        </a>
      @endforeach
    </div>
  @endif

  <!-- Author Box -->
  @include('partials.author-box')

  <!-- Related Articles -->
  @if ($relatedPosts->isNotEmpty())
    <div class="pt-12 border-t border-slate-200 dark:border-slate-800">
      <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-8">
        {{ __('site.blog_related') }}
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($relatedPosts as $related)
          <article class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center gap-4 hover:border-blue-500 transition-colors">
            <img src="{{ url_path($related->featured_image, '/images/posts/laravel-mysql-driver.jpg') }}" alt="{{ $related->title }}" class="w-20 h-20 rounded-xl object-cover bg-slate-800 flex-shrink-0">
            <div>
              <span class="text-[11px] font-mono text-blue-600 dark:text-blue-400 font-semibold">{{ loc_field($related->category, 'name') }}</span>
              <h4 class="font-bold text-sm text-slate-900 dark:text-white line-clamp-2 mt-1">
                <a href="{{ url('blog/'.$related->slug).lang_q() }}" class="hover:underline">
                  {{ loc_field($related, 'title') }}
                </a>
              </h4>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  @endif

</div>
@endsection

@push('schema')
<script type="application/ld+json">{!! schema_json(article_schema($post)) !!}</script>
<script type="application/ld+json">{!! schema_json(breadcrumb_schema($breadcrumbs)) !!}</script>
@endpush
