<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        if (!$admin->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->withErrors(['email' => 'Your account has been deactivated.']);
        }

        if ($admin->isLocked()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->withErrors(['email' => 'Account locked. Try again later.']);
        }

        if ($admin->allowed_ips) {
            $allowedIps = json_decode($admin->allowed_ips, true) ?? [];
            if (!empty($allowedIps) && !in_array($request->ip(), $allowedIps)) {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->withErrors(['email' => 'Access denied from this IP.']);
            }
        }

        return $next($request);
    }
}