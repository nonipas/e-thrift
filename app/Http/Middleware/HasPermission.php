<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        if (!auth()->user()->hasPermission($permission)) {
            //redirect to dashboard with error message
            notify()->error('You do not have permission to access this page');
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
