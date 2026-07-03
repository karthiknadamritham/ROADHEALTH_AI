<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->status !== 'approved') {
                if (!$request->is('pending-verification') && !$request->is('logout')) {
                    return redirect('/pending-verification');
                }
            } else {
                if ($request->is('pending-verification')) {
                    return redirect('/dashboard');
                }
            }
        }

        return $next($request);
    }
}
