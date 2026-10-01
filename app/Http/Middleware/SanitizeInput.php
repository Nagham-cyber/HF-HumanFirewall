<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            if (is_string($value)) {
                // إزالة HTML Tags الخطيرة
                $value = strip_tags($value, '<p><br><strong><em><ul><ol><li>');
                
                // إزالة Null bytes
                $value = str_replace(chr(0), '', $value);
                
                // trim
                $value = trim($value);
            }
        });

        $request->merge($input);

        return $next($request);
    }
}