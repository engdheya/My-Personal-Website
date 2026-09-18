<footer class="border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80 transition-colors">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

      <!-- Brand & Bio -->
      <div class="md:col-span-2 space-y-4">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
            D
          </div>
          <div>
            <span class="font-bold text-lg text-slate-900 dark:text-white tracking-tight">
              {{ site_name() }}
            </span>
            <span class="block text-xs text-slate-500 dark:text-slate-400">
              {{ author_title() }}
            </span>
          </div>
        </div>
        <p class="text-sm text-slate-600 dark:text-slate-400 max-w-md leading-relaxed">
          {{ site_description() }}
        </p>
        <div class="pt-2 text-xs text-slate-500 font-mono">
          <span>Domain: <a href="https://dheyadev.com" class="text-blue-600 dark:text-blue-400 hover:underline">dheyadev.com</a></span>
          <span class="mx-2">•</span>
          <span>Status: <span class="text-emerald-500">● Operational</span></span>
        </div>
      </div>

      <!-- Quick Navigation -->
      <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-slate-200 mb-4">
          {{ __('site.footer_quick_links') }}
        </h3>
        <ul class="space-y-2 text-sm">
          @foreach ([
              ['path' => '/', 'label' => __('site.nav_home')],
              ['path' => '/about', 'label' => __('site.nav_about')],
              ['path' => '/projects', 'label' => __('site.nav_projects')],
              ['path' => '/blog', 'label' => __('site.nav_blog')],
              ['path' => '/services', 'label' => __('site.nav_services')],
              ['path' => '/contact', 'label' => __('site.nav_contact')],
          ] as $item)
          <li>
            <a href="{{ url($item['path']) }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
              {{ $item['label'] }}
            </a>
          </li>
          @endforeach
        </ul>
      </div>

      <!-- Social & Legal -->
      <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-slate-200 mb-4">
          {{ __('site.footer_social') }}
        </h3>
        <ul class="space-y-2 text-sm mb-6">
          @foreach (['github' => 'GitHub', 'telegram' => 'Telegram', 'linkedin' => 'LinkedIn'] as $key => $label)
            @if (setting($key))
            <li>
              <a href="{{ setting($key) }}" target="_blank" rel="noopener noreferrer" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 flex items-center gap-2 transition-colors">
                <span>{{ $label }}</span>
                <span class="text-xs text-slate-400">↗</span>
              </a>
            </li>
            @endif
          @endforeach
          @if (setting('email'))
          <li>
            <a href="mailto:{{ setting('email') }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 flex items-center gap-2 transition-colors">
              <span>{{ setting('email') }}</span>
            </a>
          </li>
          @endif
        </ul>

        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-slate-200 mb-2">
          {{ __('site.footer_legal') }}
        </h3>
        <ul class="space-y-1.5 text-xs">
          <li>
            <a href="{{ url('privacy-policy') }}" class="text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
              {{ __('site.privacy_title') }}
            </a>
          </li>
          <li>
            <a href="{{ url('terms') }}" class="text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
              {{ __('site.terms_title') }}
            </a>
          </li>
          <li>
            <a href="{{ url('sitemap.xml') }}" target="_blank" class="text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
              Sitemap.xml
            </a>
          </li>
          <li>
            <a href="{{ url('robots.txt') }}" target="_blank" class="text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
              Robots.txt
            </a>
          </li>
        </ul>
      </div>

    </div>

    <!-- Bottom copyright -->
    <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 dark:text-slate-400 gap-4">
      <div>
        © {{ date('Y') }} {{ site_name() }} — <span class="font-medium">{{ author_name() }}</span>. {{ __('site.footer_rights') }}
      </div>
      <div class="flex items-center gap-4">
        <span>Built with Clean Architecture</span>
        <span>•</span>
        <a href="{{ url('admin') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Admin CMS</a>
      </div>
    </div>
  </div>
</footer>
