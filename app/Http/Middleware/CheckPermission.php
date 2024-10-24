<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect('/login');
        }

        $user = Auth::guard('admin')->user();
        
        if ($user->permission != $permission) {
            // Redirect to appropriate dashboard based on permission
            switch ($user->permission) {
                case 1:
                    return redirect()->route('Dashboard.admin');
                case 2:
                    return redirect()->route('Dashboard.user');
                case 3:
                    return redirect()->route('Dashboard.employee');
                default:
                    return redirect('/login');
            }
        }

        return $next($request);
    }
}
    

