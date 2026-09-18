@extends('layouts.app')

@section('title', $project->seo_title ?: (loc_field($project, 'title').' | '.site_name()))
@section('meta_description', $project->meta_description ?: loc_field($project, 'short_description'))
@section('canonical', url('projects/'.$project->slug))
@section('og_image', absolute_url($project->og_image ?: $project->main_image, '/images/og-cover.png'))
@section('og_type', 'article')

@section('content')
@php
    $technologies = array_filter(array_map('trim', explode(',', (string) $project->technologies)));
    $featuresList = is_array($project->features) ? $project->features : [];
@endphp
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  @include('partials.breadcrumbs')

  <!-- Project Header -->
  <header class="mb-10 space-y-4">
    <div class="flex flex-wrap items-center gap-3">
      <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">
        {{ $project->category ?: 'Engineering Project' }}
      </span>
      <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $project->status === 'Completed' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300' }}">
        {{ $project->status === 'Completed' ? __('site.project_status_completed') : __('site.project_status_in_progress') }}
      </span>
      <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">
        {{ $project->project_date ?: '2026' }}
      </span>
    </div>

    <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight">
      {{ loc_field($project, 'title') }}
    </h1>

    <p class="text-lg text-slate-600 dark:text-slate-300 leading-relaxed max-w-3xl">
      {{ loc_field($project, 'short_description') }}
    </p>

    <!-- Links Bar -->
    <div class="flex flex-wrap items-center gap-3 pt-2">
      @if ($project->live_url)
      <a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow transition-all flex items-center gap-2">
        <span>{{ __('site.project_live_demo') }}</span>
        <span>↗</span>
      </a>
      @endif
      @if ($project->github_url)
      <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-semibold text-sm transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
        <span>{{ __('site.project_github') }}</span>
        <span>↗</span>
      </a>
      @endif
    </div>
  </header>

  <!-- Hero Image -->
  <div class="rounded-3xl overflow-hidden shadow-2xl bg-slate-900 border border-slate-200 dark:border-slate-800 mb-12 aspect-video">
    <img
      src="{{ url_path($project->main_image, '/images/projects/smart-store-main.jpg') }}"
      alt="{{ $project->title }}"
      class="w-full h-full object-cover"
      loading="eager"
    />
  </div>

  <!-- Tech Stack Grid -->
  <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 mb-12">
    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3 font-mono">
      {{ __('site.project_tech') }}
    </h3>
    <div class="flex flex-wrap gap-2">
      @foreach ($technologies as $tech)
        <span class="px-3 py-1.5 rounded-lg text-xs font-mono font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-blue-600 dark:text-blue-400 shadow-sm">
          {{ $tech }}
        </span>
      @endforeach
    </div>
  </div>

  <!-- Challenge and Solution Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
    @if ($project->problem)
    <div class="p-6 rounded-2xl bg-rose-50/50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/60">
      <h3 class="text-lg font-bold text-rose-900 dark:text-rose-200 mb-3 flex items-center gap-2">
        <span>⚠️</span>
        <span>{{ __('site.project_problem') }}</span>
      </h3>
      <p class="text-sm text-rose-800 dark:text-rose-300 leading-relaxed">
        {{ loc_field($project, 'problem') }}
      </p>
    </div>
    @endif

    @if ($project->solution)
    <div class="p-6 rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/60">
      <h3 class="text-lg font-bold text-emerald-900 dark:text-emerald-200 mb-3 flex items-center gap-2">
        <span>💡</span>
        <span>{{ __('site.project_solution') }}</span>
      </h3>
      <p class="text-sm text-emerald-800 dark:text-emerald-300 leading-relaxed">
        {{ loc_field($project, 'solution') }}
      </p>
    </div>
    @endif
  </div>

  <!-- Full Description Body -->
  <div class="prose dark:prose-invert max-w-none mb-12 text-slate-700 dark:text-slate-300 leading-relaxed space-y-4">
    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Project Overview & Architecture</h2>
    <p>{{ loc_field($project, 'description') }}</p>
  </div>

  <!-- Key Features List -->
  @if (count($featuresList) > 0)
  <div class="mb-12 p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
      <span>🚀</span>
      <span>{{ __('site.project_key_features') }}</span>
    </h3>
    <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-700 dark:text-slate-300">
      @foreach ($featuresList as $feature)
        <li class="flex items-start gap-3">
          <span class="text-emerald-500 font-bold mt-0.5">✓</span>
          <span>{{ $feature }}</span>
        </li>
      @endforeach
    </ul>
  </div>
  @endif

  <!-- Related Projects -->
  @if ($relatedProjects->isNotEmpty())
  <div class="pt-12 border-t border-slate-200 dark:border-slate-800">
    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-8">
      {{ __('site.project_related') }}
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      @foreach ($relatedProjects as $related)
        <a href="{{ url('projects/'.$related->slug).lang_q() }}" class="group p-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-blue-500 transition-colors flex items-center gap-4">
          <img src="{{ url_path($related->main_image, '/images/projects/smart-store-main.jpg') }}" alt="{{ $related->title }}" class="w-16 h-16 rounded-xl object-cover bg-slate-800">
          <div>
            <h4 class="font-bold text-sm text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">
              {{ loc_field($related, 'title') }}
            </h4>
            <span class="text-xs text-slate-500 font-mono">{{ $related->category }}</span>
          </div>
        </a>
      @endforeach
    </div>
  </div>
  @endif

</div>
@endsection

@push('schema')
<script type="application/ld+json">{!! schema_json(breadcrumb_schema($breadcrumbs)) !!}</script>
@endpush
