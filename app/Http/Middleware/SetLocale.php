<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // الأولوية: جلسة → تفضيل المستخدم → الافتراضي
        $locale = Session::get('locale');

        if (!$locale && Auth::check() && Auth::user()->language_preference) {
            $locale = Auth::user()->language_preference;
        }

        if (!$locale) {
            $locale = config('app.locale', 'en');
        }

        // التحقق من أن اللغة مدعومة
        $availableLocales = ['en', 'ar'];
        if (!in_array($locale, $availableLocales)) {
            $locale = 'en';
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }
}