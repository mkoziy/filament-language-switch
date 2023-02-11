<?php

namespace BezhanSalleh\FilamentLanguageSwitch\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SwitchLanguageLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = session()->get('locale') ?? $request->get('locale') ?? $request->cookie('locale') ?? config('app.locale', 'en');

        if (array_key_exists($locale, config('filament-language-switch.locales'))) {
            app()->setLocale($locale);

            return $this->addLocaleCookie($next($request), $locale);
        }

        return $next($request);
    }

    private function addLocaleCookie(Response $response, string $locale)
    {
        $response->headers->setCookie(new Cookie('locale', $locale));

        return $response;
    }
}
