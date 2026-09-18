@php
    $position = $position ?? null;
    $adsEnabled = (string) setting('adsense_enabled') === '1';

    $adCode = match ($position) {
        'top' => setting('top_ad_code'),
        'article' => setting('article_ad_code'),
        'sidebar' => setting('sidebar_ad_code'),
        'footer' => setting('footer_ad_code'),
        default => null,
    };
@endphp

@if ($adsEnabled && $adCode)
  @if ($position === 'top')
    <div class="my-6 text-center overflow-hidden max-w-full">
      <div class="text-[10px] uppercase tracking-wider text-slate-400 mb-1">Sponsored</div>
      {!! $adCode !!}
    </div>
  @elseif ($position === 'article')
    <div class="my-8 text-center overflow-hidden max-w-full border-y border-slate-200 dark:border-slate-800 py-4">
      <div class="text-[10px] uppercase tracking-wider text-slate-400 mb-1">Sponsored</div>
      {!! $adCode !!}
    </div>
  @elseif ($position === 'sidebar')
    <div class="my-6 text-center overflow-hidden max-w-full rounded-xl bg-slate-100 dark:bg-slate-800/40 p-3">
      <div class="text-[10px] uppercase tracking-wider text-slate-400 mb-1">Sponsored</div>
      {!! $adCode !!}
    </div>
  @elseif ($position === 'footer')
    <div class="my-8 text-center overflow-hidden max-w-full">
      <div class="text-[10px] uppercase tracking-wider text-slate-400 mb-1">Sponsored</div>
      {!! $adCode !!}
    </div>
  @endif
@endif
