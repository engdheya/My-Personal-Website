<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps the interface language (Arabic / English) between requests.
 *
 * Resolution order: ?lang= query string  >  session  >  cookie  >  config('app.locale').
 * Mirrors the behaviour of the SSR engine (cookie `dheyadev_lang`, default `ar`).
 */
class SetLocale
{
    /** @var array<int, string> */
    public const SUPPORTED = ['ar', 'en'];

    public const SESSION_KEY = 'locale';

    public const COOKIE_KEY = 'dheyadev_lang';

    public function handle(Request $request, Closure $next): Response
    {
        $requested = $request->query('lang');
        $persist = false;

        if (is_string($requested) && in_array($requested, self::SUPPORTED, true)) {
            $locale = $requested;
            $persist = true;
        } else {
            $locale = $request->session()->get(self::SESSION_KEY)
                ?: $request->cookie(self::COOKIE_KEY);
        }

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = (string) config('app.locale', 'ar');
        }

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = 'ar';
        }

        App::setLocale($locale);

        if ($persist) {
            $request->session()->put(self::SESSION_KEY, $locale);
        }

        $response = $next($request);

        if ($persist) {
            $response->headers->setCookie(
                cookie(self::COOKIE_KEY, $locale, 60 * 24 * 365, null, null, false, false)
            );
        }

        return $response;
    }
}
