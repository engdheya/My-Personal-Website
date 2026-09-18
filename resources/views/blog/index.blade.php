@extends('layouts.app')

@section('title', ($currentCategory
    ? (is_rtl() ? ($currentCategory->seo_title ?: $currentCategory->name_ar ?: $currentCategory->name) : ($currentCategory->seo_title ?: $currentCategory->name))
    : __('site.all_articles_title')).' | '.site_name())

@section('meta_description', $currentCategory
    ? ($currentCategory->meta_description ?: loc_field($currentCategory, 'description'))
    : site_description())

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  @include('partials.breadcrumbs')

  <div class="flex flex-col lg:flex-row gap-12">

    <!-- Main Content Area -->
    <div class="flex-1 min-w-0">

      <!-- Blog Title & Intro -->
      <div class="max-w-3xl mb-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
          {{ $currentCategory ? loc_field($currentCategory, 'name') : __('site.all_articles_title') }}
        </h1>
        <p class="mt-2 text-base text-slate-600 dark:text-slate-400">
          {{ $currentCategory ? loc_field($currentCategory, 'description') : __('site.all_articles_subtitle') }}
        </p>
      </div>

      <!-- Categories Filter Tabs -->
      <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 border-b border-slate-200 dark:border-slate-800 scrollbar-none">
        <a href="{{ url('blog').lang_q() }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ ! $selectedCategorySlug ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
          {{ is_rtl() ? 'الكل' : 'All Topics' }}
        </a>
        @foreach ($categories as $cat)
          <a href="{{ url('blog').'?category='.$cat->slug.(app()->getLocale() === 'en' ? '&lang=en' : '') }}" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $selectedCategorySlug === $cat->slug ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
            {{ loc_field($cat, 'name') }}
          </a>
        @endforeach
      </div>

      <!-- Articles Grid -->
      @if ($posts->isEmpty())
        <div class="p-12 text-center rounded-3xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
          <p class="text-slate-500">{{ __('site.blog_no_articles') }}</p>
        </div>
      @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          @foreach ($posts as $post)
            <article class="group rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
              <!-- Thumbnail -->
              <a href="{{ url('blog/'.$post->slug).lang_q() }}" class="block aspect-video overflow-hidden bg-slate-100 dark:bg-slate-800 relative">
                <img
                  src="{{ url_path($post->featured_image, '/images/posts/laravel-mysql-driver.jpg') }}"
                  alt="{{ $post->alt_text ?: $post->title }}"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  loading="lazy"
                />
                <span class="absolute top-3 start-3 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-600/90 text-white backdrop-blur-sm">
                  {{ loc_field($post->category, 'name') }}
                </span>
              </a>

              <!-- Details -->
              <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                <div>
                  <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mb-2 font-mono">
                    <span>{{ jdate($post->published_at) }}</span>
                    <span>•</span>
                    <span>{{ $post->reading_time ?: 5 }} {{ __('site.blog_reading_time') }}</span>
                  </div>
                  <h2 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2 leading-snug">
                    <a href="{{ url('blog/'.$post->slug).lang_q() }}">
                      {{ loc_field($post, 'title') }}
                    </a>
                  </h2>
                  <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                    {{ loc_field($post, 'excerpt') }}
                  </p>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-semibold">
                  <span class="text-slate-500 font-mono">
                    {{ author_name() }}
                  </span>
                  <a href="{{ url('blog/'.$post->slug).lang_q() }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                    <span>{{ __('site.blog_read_article') }}</span>
                    <span class="rtl:rotate-180">→</span>
                  </a>
                </div>

              </div>
            </article>
          @endforeach
        </div>

        <!-- Pagination -->
        @include('partials.pagination', ['paginator' => $posts])
      @endif

    </div>

    <!-- Desktop Sidebar -->
    <aside class="w-full lg:w-80 flex-shrink-0 space-y-8">

      <!-- Search Box -->
      <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3">
          {{ is_rtl() ? 'بحث في المدونة' : 'Search Articles' }}
        </h3>
        <form action="{{ url('search') }}" method="GET" class="relative">
          @if (app()->getLocale() === 'en')
            <input type="hidden" name="lang" value="en">
          @endif
          <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="{{ __('site.blog_search_placeholder') }}"
            class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none"
            required
          >
          <button type="submit" class="absolute end-2 top-2 p-1 text-slate-400 hover:text-blue-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </button>
        </form>
      </div>

      <!-- Categories Widget -->
      <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4">
          {{ __('site.blog_categories') }}
        </h3>
        <ul class="space-y-2 text-xs">
          @foreach ($categories as $cat)
            <li>
              <a href="{{ url('blog').'?category='.$cat->slug.(app()->getLocale() === 'en' ? '&lang=en' : '') }}" class="flex items-center justify-between text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors py-1">
                <span>{{ loc_field($cat, 'name') }}</span>
                <span class="text-[11px] font-mono px-2 py-0.5 rounded-full bg-slate-200 dark:bg-slate-800">{{ $cat->posts_count ?? 1 }}</span>
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      <!-- Tags Widget -->
      <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4">
          {{ __('site.blog_tags') }}
        </h3>
        <div class="flex flex-wrap gap-1.5">
          @foreach ($tags as $tag)
            <a href="{{ url('search').'?q='.urlencode($tag->name).(app()->getLocale() === 'en' ? '&lang=en' : '') }}" class="px-2.5 py-1 rounded-lg text-xs font-mono bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-blue-500 hover:text-blue-600 transition-colors">
              #{{ $tag->name }}
            </a>
          @endforeach
        </div>
      </div>

      <!-- Sidebar Ad Slot -->
      @include('partials.ads', ['position' => 'sidebar'])

    </aside>

  </div>
</div>
@endsection

@push('schema')
<script type="application/ld+json">{!! schema_json(breadcrumb_schema($breadcrumbs)) !!}</script>
@endpush
