<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * تبديل اللغة
     */
    public function switch($locale)
    {
        // اللغات المتاحة
        $availableLocales = ['en', 'ar'];

        if (!in_array($locale, $availableLocales)) {
            return back();
        }

        // حفظ اللغة في الجلسة
        Session::put('locale', $locale);
        App::setLocale($locale);

        // حفظ اللغة في حساب المستخدم إذا كان مسجل دخول
        if (Auth::check()) {
            Auth::user()->update(['language_preference' => $locale]);
        }

        return back();
    }
}