@extends('layouts.app')

@section('title', is_rtl()
    ? (setting('author_name_ar') ?: 'ضياء عباس').' | '.(setting('author_title_ar') ?: 'مهندس برمجيات')
    : (setting('author_name') ?: 'Dheya Abbas').' | '.(setting('author_title') ?: 'Software Engineer & Developer'))

@section('meta_description', site_description())

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28 border-b border-slate-100 dark:border-slate-850">
  <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(59,130,246,0.15),rgba(255,255,255,0))] dark:bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(37,99,235,0.2),rgba(15,23,42,0))]"></div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col-reverse lg:flex-row items-center justify-between gap-12">

      <!-- Left Content -->
      <div class="flex-1 text-center lg:text-start space-y-6">

        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 text-xs font-semibold">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          <span>{{ __('site.hero_subtitle') }}</span>
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-[1.15]">
          {{ __('site.hero_greeting') }} <br class="hidden sm:inline">
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 dark:from-blue-400 dark:via-indigo-400 dark:to-purple-400">
            {{ author_name() }}
          </span>
        </h1>

        <p class="text-lg sm:text-xl font-medium text-slate-600 dark:text-slate-300 font-mono">
          {{ author_title() }}
        </p>

        <p class="text-base text-slate-600 dark:text-slate-400 max-w-2xl leading-relaxed">
          {{ setting(is_rtl() ? 'author_bio_ar' : 'author_bio') ?: __('site.hero_description') }}
        </p>

        <!-- CTA Action Buttons -->
        <div class="pt-4 flex flex-wrap items-center justify-center lg:justify-start gap-3">
          <a href="{{ url('projects').lang_q() }}" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
            {{ __('site.hero_btn_projects') }}
          </a>
          <a href="{{ url('blog').lang_q() }}" class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-900 dark:text-slate-100 font-semibold text-sm border border-slate-200 dark:border-slate-700 transition-all transform hover:-translate-y-0.5">
            {{ __('site.hero_btn_blog') }}
          </a>
          <a href="{{ url('contact').lang_q() }}" class="px-6 py-3 rounded-xl bg-transparent hover:bg-slate-100 dark:hover:bg-slate-850 text-slate-700 dark:text-slate-300 font-semibold text-sm border border-slate-300 dark:border-slate-700 transition-all">
            {{ __('site.hero_btn_contact') }}
          </a>
          @if (setting('cv_url'))
          <a href="{{ url_path(setting('cv_url')) }}" download class="px-5 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold text-sm border border-emerald-300 dark:border-emerald-800 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>{{ __('site.hero_btn_cv') }}</span>
          </a>
          @endif
        </div>

      </div>

      <!-- Right Avatar / Visual Card -->
      <div class="relative flex-shrink-0">
        <div class="w-64 h-64 sm:w-80 sm:h-80 rounded-3xl bg-gradient-to-tr from-blue-600 via-indigo-500 to-purple-600 p-1 shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-300">
          <img
            src="{{ url_path(setting('profile_image'), '/images/dheya-profile.jpg') }}"
            alt="{{ setting('author_name') ?: 'Dheya Abbas' }}"
            class="w-full h-full object-cover rounded-[22px] bg-slate-900"
            loading="eager"
            width="320"
            height="320"
          />
        </div>
        <!-- Floating Experience Tag -->
        <div class="absolute -bottom-4 -left-4 sm:left-auto sm:-right-4 px-4 py-2.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold">
            ⚡
          </div>
          <div class="text-xs">
            <div class="font-bold text-slate-900 dark:text-white">Full-Stack & Mobile</div>
            <div class="text-slate-500 dark:text-slate-400 font-mono">Laravel + Flutter</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Skills Section -->
<section class="py-16 bg-slate-50/50 dark:bg-slate-900/30 border-b border-slate-200 dark:border-slate-800">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-12">
      <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
        {{ __('site.skills_title') }}
      </h2>
      <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
        {{ __('site.skills_subtitle') }}
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

      @php
          $skillGroups = [
              ['icon' => '🐘', 'title' => __('site.skills_backend'), 'tint' => 'bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400', 'items' => [
                  ['Laravel', 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-200 dark:border-rose-900'],
                  ['PHP 8+', 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-900'],
                  ['REST APIs', 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 border border-blue-200 dark:border-blue-900'],
                  ['Sanctum', 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'],
                  ['OOP / SOLID', 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'],
              ]],
              ['icon' => '📱', 'title' => __('site.skills_mobile'), 'tint' => 'bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400', 'items' => [
                  ['Flutter', 'bg-sky-50 text-sky-700 dark:bg-sky-950/40 dark:text-sky-300 border border-sky-200 dark:border-sky-900'],
                  ['Dart', 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 border border-blue-200 dark:border-blue-900'],
                  ['Riverpod', 'bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 border border-purple-200 dark:border-purple-900'],
                  ['Dio', 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'],
                  ['Clean Architecture', 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'],
              ]],
              ['icon' => '🗄️', 'title' => __('site.skills_database'), 'tint' => 'bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400', 'items' => [
                  ['MySQL', 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200 dark:border-amber-900'],
                  ['SQLite', 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900'],
                  ['Redis', 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-300 border border-red-200 dark:border-red-900'],
                  ['Indexing', 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'],
                  ['Query Optimization', 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'],
              ]],
              ['icon' => '🛠️', 'title' => __('site.skills_tools'), 'tint' => 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400', 'items' => [
                  ['Git', 'bg-orange-50 text-orange-700 dark:bg-orange-950/40 dark:text-orange-300 border border-orange-200 dark:border-orange-900'],
                  ['GitHub', 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700'],
                  ['VS Code', 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 border border-blue-200 dark:border-blue-900'],
                  ['Postman', 'bg-orange-50 text-orange-700 dark:bg-orange-950/40 dark:text-orange-300 border border-orange-200 dark:border-orange-900'],
                  ['Tailwind CSS', 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'],
              ]],
          ];
      @endphp

      @foreach ($skillGroups as $group)
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
          <div class="w-10 h-10 rounded-xl {{ $group['tint'] }} flex items-center justify-center font-bold mb-4">
            {{ $group['icon'] }}
          </div>
          <h3 class="font-bold text-base text-slate-900 dark:text-white mb-3">
            {{ $group['title'] }}
          </h3>
          <div class="flex flex-wrap gap-2">
            @foreach ($group['items'] as [$label, $classes])
              <span class="px-2.5 py-1 rounded-md text-xs font-semibold {{ $classes }}">{{ $label }}</span>
            @endforeach
          </div>
        </div>
      @endforeach

    </div>
  </div>
</section>

<!-- About Section Preview -->
<section class="py-16 border-b border-slate-200 dark:border-slate-800">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-br from-blue-50/50 via-indigo-50/30 to-purple-50/20 dark:from-slate-900 dark:via-slate-900/80 dark:to-slate-950 border border-blue-100 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-8">
      <div class="space-y-4 max-w-2xl text-center md:text-start">
        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
          {{ __('site.about_title') }}
        </h2>
        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed">
          {{ setting(is_rtl() ? 'author_bio_ar' : 'author_bio') ?: __('site.hero_description') }}
        </p>
        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 pt-2 text-xs font-medium text-slate-500 dark:text-slate-400">
          <span class="flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            {{ is_rtl() ? 'تصميم بنية برمجية متماسكة' : 'Clean & Scalable Architecture' }}
          </span>
          <span class="flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            {{ is_rtl() ? 'حلول عملية ومجربة' : 'Production-Ready Solutions' }}
          </span>
          <span class="flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
            {{ is_rtl() ? 'مشاركة المعرفة التقنية' : 'Knowledge Sharing' }}
          </span>
        </div>
      </div>
      <a href="{{ url('about').lang_q() }}" class="px-6 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-900 font-semibold text-sm shadow-md transition-all flex items-center gap-2 flex-shrink-0">
        <span>{{ __('site.about_read_more') }}</span>
        <span class="rtl:rotate-180">→</span>
      </a>
    </div>
  </div>
</section>

<!-- Featured Projects Section -->
<section class="py-16 border-b border-slate-200 dark:border-slate-800">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
      <div>
        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
          {{ __('site.featured_projects_title') }}
        </h2>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
          {{ __('site.featured_projects_subtitle') }}
        </p>
      </div>
      <a href="{{ url('projects').lang_q() }}" class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">
        <span>{{ __('site.view_all_projects') }}</span>
        <span class="rtl:rotate-180">→</span>
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach ($featuredProjects as $project)
        <article class="group rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
          <!-- Thumbnail -->
          <a href="{{ url('projects/'.$project->slug).lang_q() }}" class="block aspect-video overflow-hidden bg-slate-100 dark:bg-slate-800 relative">
            <img
              src="{{ url_path($project->main_image, '/images/projects/smart-store-main.jpg') }}"
              alt="{{ $project->title }}"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
            <span class="absolute top-3 end-3 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider backdrop-blur-md {{ $project->status === 'Completed' ? 'bg-emerald-500/90 text-white' : 'bg-amber-500/90 text-white' }}">
              {{ $project->status === 'Completed' ? __('site.project_status_completed') : __('site.project_status_in_progress') }}
            </span>
          </a>

          <!-- Details -->
          <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
            <div>
              <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-2 font-mono">
                <span>{{ $project->category ?: 'Software' }}</span>
                <span>•</span>
                <span>{{ $project->project_date ?: '2026' }}</span>
              </div>
              <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-1">
                <a href="{{ url('projects/'.$project->slug).lang_q() }}">
                  {{ loc_field($project, 'title') }}
                </a>
              </h3>
              <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                {{ loc_field($project, 'short_description') }}
              </p>
            </div>

            <div class="pt-2 border-t border-slate-100 dark:border-slate-800 space-y-3">
              <!-- Tech tags -->
              <div class="flex flex-wrap gap-1.5 text-[11px] font-mono">
                @foreach (array_slice(array_values(array_filter(array_map('trim', explode(',', (string) $project->technologies)))), 0, 4) as $tech)
                  <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                    {{ $tech }}
                  </span>
                @endforeach
              </div>

              <!-- Links -->
              <div class="flex items-center justify-between pt-1 text-xs font-semibold">
                <a href="{{ url('projects/'.$project->slug).lang_q() }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                  {{ __('site.view_details') }} →
                </a>
                <div class="flex items-center gap-3">
                  @if ($project->github_url)
                  <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-slate-900 dark:hover:text-white" title="GitHub">
                    {{ __('site.project_github') }} ↗
                  </a>
                  @endif
                  @if ($project->live_url)
                  <a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" class="text-emerald-600 dark:text-emerald-400 hover:underline" title="Live Demo">
                    {{ __('site.project_live_demo') }} ↗
                  </a>
                  @endif
                </div>
              </div>
            </div>

          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>

<!-- Latest Articles Section -->
<section class="py-16 border-b border-slate-200 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-900/20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
      <div>
        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
          {{ __('site.latest_articles_title') }}
        </h2>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
          {{ __('site.latest_articles_subtitle') }}
        </p>
      </div>
      <a href="{{ url('blog').lang_q() }}" class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">
        <span>{{ __('site.all_articles_title') }}</span>
        <span class="rtl:rotate-180">→</span>
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach ($latestPosts as $post)
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
              <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2 leading-snug">
                <a href="{{ url('blog/'.$post->slug).lang_q() }}">
                  {{ loc_field($post, 'title') }}
                </a>
              </h3>
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
  </div>
</section>

<!-- Services Preview Section -->
<section class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-12">
      <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
        {{ __('site.services_title') }}
      </h2>
      <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
        {{ __('site.services_subtitle') }}
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach ($services as $service)
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
          <div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl mb-4 font-bold">
              ⚡
            </div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">
              {{ loc_field($service, 'title') }}
            </h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
              {{ loc_field($service, 'short_description') }}
            </p>
          </div>
          <a href="{{ url('contact').'?service='.urlencode($service->title).(app()->getLocale() === 'en' ? '&lang=en' : '') }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline pt-3 border-t border-slate-100 dark:border-slate-800">
            <span>{{ __('site.services_request') }}</span>
            <span class="rtl:rotate-180">→</span>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endsection

@push('schema')
<script type="application/ld+json">{!! schema_json(person_schema()) !!}</script>
@endpush
