@extends('layouts.app')

@section('title', '404 - Page Not Found | DheyaDev')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-24 text-center">
  <div class="w-20 h-20 mx-auto rounded-3xl bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 flex items-center justify-center text-3xl font-extrabold mb-6">
    404
  </div>
  <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-4">
    Page Not Found
  </h1>
  <p class="text-base text-slate-600 dark:text-slate-400 mb-8 max-w-md mx-auto">
    The page you are looking for does not exist or has been moved.
  </p>
  <div class="flex items-center justify-center gap-4">
    <a href="/" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow transition-colors">
      Back to Homepage
    </a>
  </div>
</div>
@endsection
