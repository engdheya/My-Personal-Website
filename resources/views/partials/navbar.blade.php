@php
    // Highlight the current section in the navigation.
    $activePage = $activePage ?? match (true) {
        request()->routeIs('home') => 'home',
        request()->routeIs('about') => 'about',
        request()->routeIs('projects.*') => 'projects',
        request()->routeIs('blog.*') => 'blog',
        request()->routeIs('services.*') => 'services',
        request()->routeIs('contact.*') => 'contact',
        default => '',
    };

    $navClass = 'px-3 py-2 rounded-lg text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors';
    $navActive = 'text-blue-600 dark:text-blue-400 font-semibold bg-blue-50/50 dark:bg-blue-900/20';
    $mobileClass = 'block px-3 py-2 rounded-lg text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800';
    $mobileActive = 'text-blue-600 font-bold bg-blue-50 dark:bg-blue-900/20';

    $links = [
        ['key' => 'home', 'route' => '/', 'label' => __('site.nav_home')],
        ['key' => 'about', 'route' => '/about', 'label' => __('site.nav_about')],
        ['key' => 'projects', 'route' => '/projects', 'label' => __('site.nav_projects')],
        ['key' => 'blog', 'route' => '/blog', 'label' => __('site.nav_blog')],
        ['key' => 'services', 'route' => '/services', 'label' => __('site.nav_services')],
        ['key' => 'contact', 'route' => '/contact', 'label' => __('site.nav_contact')],
    ];

    $withLang = fn (string $path) => url($path).(app()->getLocale() === 'en' ? '?lang=en' : '');
@endphp

<header class="sticky top-0 z-40 w-full backdrop-blur flex-none transition-colors duration-500 border-b border-slate-200/80 dark:border-slate-800/80 bg-white/90 dark:bg-slate-900/90">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">

      <!-- Brand Logo -->
      <a href="{{ $withLang('/') }}" class="flex items-center gap-3 group">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-xl shadow-md group-hover:scale-105 transition-transform">
          D
        </div>
        <div class="flex flex-col">
          <span class="font-bold text-lg text-slate-900 dark:text-white tracking-tight leading-none group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
            {{ site_name() }}
          </span>
          <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono leading-tight mt-0.5">
            {{ author_title() }}
          </span>
        </div>
      </a>

      <!-- Desktop Navigation Links -->
      <nav class="hidden md:flex items-center space-x-1 rtl:space-x-reverse font-medium text-sm">
        @foreach ($links as $item)
          <a href="{{ $withLang($item['route']) }}" class="{{ $navClass }} {{ $activePage === $item['key'] ? $navActive : '' }}">
            {{ $item['label'] }}
          </a>
        @endforeach
      </nav>

      <!-- Right Controls: Search, Theme, Language, GitHub -->
      <div class="flex items-center gap-2">

        <!-- Search -->
        <a href="{{ $withLang('/search') }}" title="{{ __('site.search_title') }}" class="p-2 rounded-lg text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </a>

        <!-- GitHub -->
        @if (setting('github'))
        <a href="{{ setting('github') }}" target="_blank" rel="noopener noreferrer" title="GitHub" class="hidden sm:inline-flex p-2 rounded-lg text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
        </a>
        @endif

        <!-- Dark / Light Mode Switcher -->
        <button type="button" id="theme-toggle" aria-label="{{ __('site.theme_dark') }}" class="p-2 rounded-lg text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
          <!-- Sun (dark mode) -->
          <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          <!-- Moon (light mode) -->
          <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        </button>

        <!-- Language Switcher -->
        <a href="{{ request()->fullUrlWithQuery(['lang' => app()->getLocale() === 'ar' ? 'en' : 'ar']) }}" class="px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
          {{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}
        </a>

        <!-- Mobile Menu Button -->
        <button type="button" id="mobile-menu-btn" aria-label="Toggle mobile menu" class="md:hidden p-2 rounded-lg text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

      </div>
    </div>
  </div>

  <!-- Mobile Drawer Menu -->
  <div id="mobile-menu" class="hidden md:hidden border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 pt-2 pb-4 space-y-1">
    @foreach ($links as $item)
      <a href="{{ $withLang($item['route']) }}" class="{{ $mobileClass }} {{ $activePage === $item['key'] ? $mobileActive : '' }}">
        {{ $item['label'] }}
      </a>
    @endforeach
    <div class="pt-2 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
      <a href="{{ url('admin') }}" class="text-xs text-slate-500 hover:text-blue-600">
        {{ __('site.nav_admin') }}
      </a>
      @if (setting('cv_url'))
      <a href="{{ url_path(setting('cv_url')) }}" download class="text-xs text-blue-600 font-semibold hover:underline">
        {{ __('site.hero_btn_cv') }}
      </a>
      @endif
    </div>
  </div>
</header>
