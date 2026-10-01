<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // فقط افحص الـ guard المطلوب
            if ($guard === 'admin') {
                // إذا كان الأدمن مسجل دخول → رجّعه للوحة تحكم الأدمن
                if (Auth::guard('admin')->check()) {
                    return redirect()->route('admin.dashboard');
                }
                // إذا لم يكن مسجل → دعنا نكمل (لا تفحص المستخدم العادي)
                continue;
            }

            // للـ web guard (المستخدم العادي) — افحص فقط إذا لم يكن هناك guard محدد
            if ($guard === null || $guard === 'web') {
                if (Auth::guard($guard)->check()) {
                    return redirect(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}