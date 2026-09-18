@extends('layouts.app')

@section('title', __('site.nav_contact').' | '.site_name())
@section('meta_description', 'Get in touch with Dheya Abbas for software engineering consultations, custom web & mobile development, and architecture reviews.')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  @include('partials.breadcrumbs')

  <!-- Header -->
  <div class="max-w-3xl mb-12">
    <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight">
      {{ __('site.contact_title') }}
    </h1>
    <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">
      {{ __('site.contact_subtitle') }}
    </p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

    <!-- Contact Form -->
    <div class="lg:col-span-2 p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
      <form action="{{ url('contact') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Spam honeypot: must remain empty! -->
        <input type="text" name="_gotcha" value="" class="hidden" tabindex="-1" autocomplete="off">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
              {{ __('site.contact_name') }} <span class="text-rose-500">*</span>
            </label>
            <input
              type="text"
              id="name"
              name="name"
              value="{{ old('name') }}"
              required
              maxlength="100"
              placeholder="e.g. John Doe"
              class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border @error('name') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
            @error('name')
              <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
              {{ __('site.contact_email') }} <span class="text-rose-500">*</span>
            </label>
            <input
              type="email"
              id="email"
              name="email"
              value="{{ old('email') }}"
              required
              maxlength="150"
              placeholder="name@example.com"
              class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border @error('email') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
            @error('email')
              <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div>
          <label for="subject" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
            {{ __('site.contact_subject') }} <span class="text-rose-500">*</span>
          </label>
          <input
            type="text"
            id="subject"
            name="subject"
            required
            maxlength="200"
            value="{{ old('subject', $prefillSubject) }}"
            placeholder="e.g. Project Inquiry / Consultation"
            class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border @error('subject') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
          >
          @error('subject')
            <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="message" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
            {{ __('site.contact_message') }} <span class="text-rose-500">*</span>
          </label>
          <textarea
            id="message"
            name="message"
            rows="5"
            required
            maxlength="3000"
            placeholder="Tell me about your project, timeline, and goals..."
            class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border @error('message') border-rose-400 @else border-slate-200 dark:border-slate-700 @enderror text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
          >{{ old('message') }}</textarea>
          @error('message')
            <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
          @enderror
        </div>

        <button
          type="submit"
          class="w-full py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center justify-center gap-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
          <span>{{ __('site.contact_submit') }}</span>
        </button>

      </form>
    </div>

    <!-- Direct Info Cards -->
    <div class="space-y-6">
      <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-4">
        <h3 class="font-bold text-lg text-slate-900 dark:text-white">
          {{ __('site.contact_info') }}
        </h3>

        <div class="space-y-3 text-sm">
          @if (setting('email'))
          <div class="flex items-start gap-3">
            <span class="text-blue-500 font-bold">✉️</span>
            <div>
              <div class="text-xs text-slate-400">{{ __('site.contact_email_direct') }}</div>
              <a href="mailto:{{ setting('email') }}" class="font-mono text-slate-900 dark:text-slate-200 hover:text-blue-600">
                {{ setting('email') }}
              </a>
            </div>
          </div>
          @endif

          @if (setting('telegram'))
          <div class="flex items-start gap-3">
            <span class="text-sky-500 font-bold">✈️</span>
            <div>
              <div class="text-xs text-slate-400">{{ __('site.contact_telegram_direct') }}</div>
              <a href="{{ setting('telegram') }}" target="_blank" rel="noopener noreferrer" class="font-mono text-slate-900 dark:text-slate-200 hover:text-blue-600">
                {{ '@'.ltrim(parse_url((string) setting('telegram'), PHP_URL_PATH) ?: '@engdheya', '/') }}
              </a>
            </div>
          </div>
          @endif

          <div class="flex items-start gap-3">
            <span class="text-emerald-500 font-bold">📍</span>
            <div>
              <div class="text-xs text-slate-400">{{ __('site.contact_location') }}</div>
              <div class="text-slate-900 dark:text-slate-200">{{ __('site.contact_location_val') }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Security / Response SLA badge -->
      <div class="p-6 rounded-3xl bg-blue-50/50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/40 text-xs text-slate-600 dark:text-slate-300 space-y-2">
        <div class="font-bold text-blue-900 dark:text-blue-300 flex items-center gap-1.5">
          <span>⚡</span>
          <span>Fast Response Guaranteed</span>
        </div>
        <p>I typically respond to inquiries within 24 hours. Messages are stored securely and never shared with third parties.</p>
      </div>
    </div>

  </div>
</div>
@endsection

@push('schema')
<script type="application/ld+json">{!! schema_json(breadcrumb_schema($breadcrumbs)) !!}</script>
@endpush
