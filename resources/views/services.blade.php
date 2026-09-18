@extends('layouts.app')

@section('title', __('site.nav_services').' | '.site_name())
@section('meta_description', 'Professional software engineering services: Laravel backend development, Flutter mobile apps, RESTful API design, and MySQL database optimization.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  @include('partials.breadcrumbs')

  <!-- Header -->
  <div class="max-w-3xl mb-12">
    <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight">
      {{ __('site.services_title') }}
    </h1>
    <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">
      {{ __('site.services_subtitle') }}
    </p>
  </div>

  <!-- Services Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
    @foreach ($services as $service)
      @php
          $featuresList = is_array($service->features) ? $service->features : [];
      @endphp
      <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between space-y-6">
        <div>
          <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-2xl mb-6 shadow-md">
            ⚡
          </div>
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">
            {{ loc_field($service, 'title') }}
          </h2>
          <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
            {{ loc_field($service, 'description') }}
          </p>

          @if (count($featuresList) > 0)
            <div class="space-y-2 pt-4 border-t border-slate-100 dark:border-slate-800">
              <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 font-mono">{{ __('site.services_features') }}</h4>
              <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-400">
                @foreach ($featuresList as $feature)
                  <li class="flex items-start gap-2">
                    <span class="text-emerald-500 font-bold">✓</span>
                    <span>{{ $feature }}</span>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>

        <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
          <a href="{{ url('contact').'?service='.urlencode($service->title).(app()->getLocale() === 'en' ? '&lang=en' : '') }}" class="w-full py-3 rounded-xl bg-slate-100 hover:bg-blue-600 dark:bg-slate-800 dark:hover:bg-blue-600 text-slate-900 hover:text-white dark:text-white font-semibold text-xs transition-colors flex items-center justify-center gap-2">
            <span>{{ __('site.services_request') }}</span>
            <span class="rtl:rotate-180">→</span>
          </a>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Bottom CTA -->
  <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-xl text-center md:text-start flex flex-col md:flex-row items-center justify-between gap-6">
    <div class="space-y-2 max-w-xl">
      <h3 class="text-2xl sm:text-3xl font-extrabold">Have a custom software project in mind?</h3>
      <p class="text-sm text-blue-100">Let's discuss how we can turn your architectural ideas into robust production software.</p>
    </div>
    <a href="{{ url('contact').lang_q() }}" class="px-8 py-3.5 rounded-xl bg-white text-blue-600 font-bold text-sm shadow hover:bg-blue-50 transition-colors flex-shrink-0">
      {{ __('site.nav_contact') }}
    </a>
  </div>

</div>
@endsection

@push('schema')
<script type="application/ld+json">{!! schema_json(breadcrumb_schema($breadcrumbs)) !!}</script>
@endpush
