@extends('layouts.app')

@section('title', __('site.nav_about').' | '.site_name())
@section('meta_description', author_bio())

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  @include('partials.breadcrumbs')

  <!-- Top Hero Info -->
  <div class="p-8 sm:p-12 rounded-3xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center gap-10 mb-16 shadow-sm">
    <div class="w-44 h-44 sm:w-52 sm:h-52 rounded-3xl overflow-hidden bg-slate-200 dark:bg-slate-800 shadow-xl flex-shrink-0 border-4 border-white dark:border-slate-800">
      <img
        src="{{ url_path(setting('profile_image'), '/images/dheya-profile.jpg') }}"
        alt="{{ setting('author_name') ?: 'Dheya Abbas' }}"
        class="w-full h-full object-cover"
        loading="eager"
      />
    </div>

    <div class="space-y-4 text-center md:text-start flex-1">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-xs font-semibold">
        <span>{{ is_rtl() ? 'مهندس برمجيات محترف' : 'Professional Software Engineer' }}</span>
      </div>

      <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
        {{ author_name() }}
      </h1>

      <p class="text-lg font-mono text-blue-600 dark:text-blue-400 font-semibold">
        {{ author_title() }}
      </p>

      <p class="text-base text-slate-600 dark:text-slate-300 max-w-3xl leading-relaxed">
        {{ setting(is_rtl() ? 'author_bio_ar' : 'author_bio') ?: __('site.hero_description') }}
      </p>

      <div class="pt-4 flex flex-wrap items-center justify-center md:justify-start gap-3">
        @if (setting('cv_url'))
        <a href="{{ url_path(setting('cv_url')) }}" download class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow transition-all flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <span>{{ __('site.hero_btn_cv') }}</span>
        </a>
        @endif
        @if (setting('github'))
        <a href="{{ setting('github') }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2.5 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-900 dark:text-white font-semibold text-sm transition-all flex items-center gap-2">
          <span>GitHub</span>
          <span>↗</span>
        </a>
        @endif
        @if (setting('telegram'))
        <a href="{{ setting('telegram') }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2.5 rounded-xl bg-sky-50 dark:bg-sky-950/40 border border-sky-300 dark:border-sky-800 text-sky-700 dark:text-sky-300 font-semibold text-sm transition-all flex items-center gap-2">
          <span>Telegram</span>
          <span>↗</span>
        </a>
        @endif
        @if (setting('email'))
        <a href="mailto:{{ setting('email') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-sm hover:bg-slate-100 dark:hover:bg-slate-800 transition-all flex items-center gap-2">
          <span>{{ setting('email') }}</span>
        </a>
        @endif
      </div>
    </div>
  </div>

  <!-- Detailed Sections: Experience, Education -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">

    <!-- Experience -->
    <div class="space-y-6">
      <h2 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
        <span>💼</span>
        <span>{{ __('site.about_experience') }}</span>
      </h2>

      <div class="space-y-4">
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
          <div class="flex items-center justify-between gap-2 mb-1">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Senior Full-Stack & Backend Engineer</h3>
            <span class="text-xs font-mono px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">2023 - Present</span>
          </div>
          <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mb-3">Enterprise Web & Mobile Solutions</p>
          <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
            Architected and engineered distributed web applications using Laravel, MySQL, and Redis. Led mobile application development with Flutter, resulting in reliable offline data synchronization and high performance.
          </p>
        </div>

        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
          <div class="flex items-center justify-between gap-2 mb-1">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Software Engineer & API Architect</h3>
            <span class="text-xs font-mono px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">2021 - 2023</span>
          </div>
          <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mb-3">SaaS & Marketplace Platforms</p>
          <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
            Designed high-throughput REST APIs, streamlined payment gateway workflows, implemented role-based permissions, and optimized complex SQL schemas to cut query latencies significantly.
          </p>
        </div>
      </div>
    </div>

    <!-- Education & Certifications -->
    <div class="space-y-6">
      <h2 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
        <span>🎓</span>
        <span>{{ __('site.about_education') }}</span>
      </h2>

      <div class="space-y-4">
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
          <div class="flex items-center justify-between gap-2 mb-1">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Bachelor of Science in Software Engineering</h3>
            <span class="text-xs font-mono px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">Graduated</span>
          </div>
          <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mb-3">Computer Science & Information Technology</p>
          <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
            Focused on Algorithms, Data Structures, Relational Database Management Systems, System Architecture, and Object-Oriented Software Design.
          </p>
        </div>

        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
          <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Technical Certifications & Continuing Education</h3>
          <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
            <li class="flex items-center gap-2">
              <span class="text-blue-500">✓</span>
              <span>Advanced Laravel Architecture & Performance Profiling</span>
            </li>
            <li class="flex items-center gap-2">
              <span class="text-blue-500">✓</span>
              <span>Flutter & Dart Cross-Platform Mobile Engineering</span>
            </li>
            <li class="flex items-center gap-2">
              <span class="text-blue-500">✓</span>
              <span>MySQL Indexing Strategies and Schema Normalization</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('schema')
<script type="application/ld+json">{!! schema_json(person_schema()) !!}</script>
<script type="application/ld+json">{!! schema_json(breadcrumb_schema($breadcrumbs)) !!}</script>
@endpush
