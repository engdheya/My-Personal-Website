<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        // SEO values: per-page @section values with global settings as fallback.
        $seoTitle = trim($__env->yieldContent('title'));
        if ($seoTitle === '') {
            $seoTitle = (string) (setting('default_seo_title') ?: (site_name().' | '.(is_rtl() ? setting('site_tagline_ar') : setting('site_tagline'))));
        }
        $seoDescription = trim($__env->yieldContent('meta_description')) ?: site_description();
        $seoCanonical = trim($__env->yieldContent('canonical')) ?: url()->current();
        $seoOgImage = trim($__env->yieldContent('og_image')) ?: absolute_url(setting('default_og_image'), '/images/og-cover.png');
        $seoOgType = trim($__env->yieldContent('og_type')) ?: 'website';
        $seoKeywords = trim($__env->yieldContent('keywords'));
        $seoRobots = trim($__env->yieldContent('robots')) ?: 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
    @endphp

    <!-- Primary Meta Tags -->
    <title>{{ $seoTitle }}</title>
    <meta name="title" content="{{ $seoTitle }}">
    <meta name="description" content="{{ $seoDescription }}">
    @if ($seoKeywords !== '')
        <meta name="keywords" content="{{ $seoKeywords }}">
    @endif
    <link rel="canonical" href="{{ $seoCanonical }}">

    <!-- Robots -->
    <meta name="robots" content="{{ $seoRobots }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $seoOgType }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $seoOgImage }}">
    <meta property="og:site_name" content="{{ site_name() }}">
    <meta property="og:locale" content="{{ is_rtl() ? 'ar_AR' : 'en_US' }}">

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $seoCanonical }}">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoOgImage }}">
    @if (setting('twitter'))
        <meta name="twitter:creator" content="{{ setting('twitter') }}">
    @endif

    <!-- Search Console Verification -->
    @if (setting('google_search_console_tag'))
        <meta name="google-site-verification" content="{{ setting('google_search_console_tag') }}">
    @endif

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ url('favicon.svg') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ public_asset('css/app.css') }}">

    <!-- Dark Mode Initializer (zero flicker) -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Schema.org JSON-LD (per page) -->
    @stack('schema')

    <!-- Google Analytics -->
    @if (setting('google_analytics_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ setting('google_analytics_id') }}');
        </script>
    @endif
</head>
<body class="bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col font-sans transition-colors duration-200 antialiased selection:bg-blue-600 selection:text-white">

    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Top Banner Ad (if enabled) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        @include('partials.ads', ['position' => 'top'])
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 dark:hover:text-white">✕</button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
            <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 dark:hover:text-white">✕</button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Ad (if enabled) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        @include('partials.ads', ['position' => 'footer'])
    </div>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Global JavaScript Helpers -->
    <script>
        // Theme Switcher
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        function updateIcons() {
            if (!darkIcon || !lightIcon) return;
            if (document.documentElement.classList.contains('dark')) {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                lightIcon.classList.add('hidden');
                darkIcon.classList.remove('hidden');
            }
        }
        updateIcons();

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', () => {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
                updateIcons();
            });
        }

        // Mobile hamburger menu
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileBtn && mobileMenu) {
            mobileBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Code snippet 1-click copy
        window.copyCode = function (button) {
            const wrapper = button.closest('.code-wrapper');
            const codeEl = wrapper ? wrapper.querySelector('pre code') : null;
            if (!codeEl) return;

            const codeText = codeEl.innerText;
            navigator.clipboard.writeText(codeText).then(() => {
                const span = button.querySelector('span');
                const originalText = span ? span.innerText : 'Copy';
                if (span) span.innerText = 'Copied!';
                button.classList.add('bg-emerald-600', 'text-white');
                setTimeout(() => {
                    if (span) span.innerText = originalText;
                    button.classList.remove('bg-emerald-600', 'text-white');
                }, 2000);
            });
        };
    </script>

    @stack('scripts')
</body>
</html>
