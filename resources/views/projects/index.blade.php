@extends('layouts.app')

@section('title', __('site.nav_projects').' | '.site_name())
@section('meta_description', 'Explore software projects, mobile apps, and systems built with Laravel, Flutter, and MySQL by Dheya Abbas.')

@section('content')
@php
    $techFilters = ['Laravel', 'Flutter', 'PHP', 'MySQL', 'REST API'];
    $idleTab = 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700';
    $activeTab = 'bg-blue-600 text-white shadow-sm';
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  @include('partials.breadcrumbs')

  <!-- Header -->
  <div class="max-w-3xl mb-10">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
      {{ __('site.nav_projects') }}
    </h1>
    <p class="mt-3 text-base text-slate-600 dark:text-slate-400">
      {{ __('site.featured_projects_subtitle') }}
    </p>
  </div>

  <!-- Tech Filter Badges -->
  <div class="flex flex-wrap items-center gap-2 mb-10 pb-4 border-b border-slate-200 dark:border-slate-800">
    <a href="{{ url('projects').lang_q() }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ ! $selectedFilter ? $activeTab : $idleTab }}">
      {{ __('site.project_filter_all') }}
    </a>
    @foreach ($techFilters as $filter)
      <a href="{{ url('projects').'?tech='.urlencode($filter).(app()->getLocale() === 'en' ? '&lang=en' : '') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $selectedFilter === $filter ? $activeTab : $idleTab }}">
        {{ $filter }}
      </a>
    @endforeach
  </div>

  <!-- Projects Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach ($projects as $project)
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

        <!-- Content -->
        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
          <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-2 font-mono">
              <span>{{ $project->category ?: 'Software' }}</span>
              <span>•</span>
              <span>{{ $project->project_date ?: '2026' }}</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-1">
              <a href="{{ url('projects/'.$project->slug).lang_q() }}">
                {{ loc_field($project, 'title') }}
              </a>
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
              {{ loc_field($project, 'short_description') }}
            </p>
          </div>

          <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-3">
            <!-- Tech badges -->
            <div class="flex flex-wrap gap-1.5 text-[11px] font-mono">
              @foreach (array_filter(array_map('trim', explode(',', (string) $project->technologies))) as $tech)
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
@endsection

@push('schema')
<script type="application/ld+json">{!! schema_json(breadcrumb_schema($breadcrumbs)) !!}</script>
@endpush
