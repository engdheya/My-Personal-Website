@extends('layouts.app')

@section('title', __('site.error_404_title'))
@section('meta_description', __('site.error_404_desc'))
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-24 text-center">
  <div class="w-20 h-20 mx-auto rounded-3xl bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 flex items-center justify-center text-3xl font-extrabold mb-6">
    404
  </div>
  <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-4">
    {{ __('site.error_404_title') }}
  </h1>
  <p class="text-base text-slate-600 dark:text-slate-400 mb-8 max-w-md mx-auto">
    {{ __('site.error_404_desc') }}
  </p>
  <div class="flex items-center justify-center gap-4">
    <a href="{{ url('/').lang_q() }}" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow transition-colors">
      {{ __('site.error_back_home') }}
    </a>
    <a href="{{ url('blog').lang_q() }}" class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-100 font-semibold text-sm transition-colors">
      {{ __('site.nav_blog') }}
    </a>
  </div>
</div>
@endsection
