<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;
use App\Models\User;

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permissions): Response
    {
        if (Helpers::hasAnyPermission($permissions)) {
            //redirect to dashboard with error message
            toastr()->error('You do not have permission to perform this action.', 'Permission Denied');
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
