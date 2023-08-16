<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOfficeType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->roles[0]->name == 'admin' && $request->user()->office->office_type == 0) {

            return abort(403, 'ليس لديك أي من الصلاحيات الضرورية لدخول هذا القسم.');
        }

        return $next($request);
    }
}
