<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lets the language come from the URL (?lang=ar / ?lang=en) so that search
 * engines and AI crawlers, which keep no session, can reach both languages.
 *
 * Order: ?lang= in the URL → language saved in the session → APP_LOCALE.
 * A ?lang= value is also saved in the session, so visitors keep their choice.
 */
class SetLocaleFromUrl
{
    public const LOCALES = ['ar', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $lang = $request->query('lang');

        if (is_string($lang) && in_array($lang, self::LOCALES, true)) {
            session(['locale' => $lang]);
            app()->setLocale($lang);
        } elseif (session()->has('locale') && in_array(session('locale'), self::LOCALES, true)) {
            app()->setLocale(session('locale'));
        }

        return $next($request);
    }
}
