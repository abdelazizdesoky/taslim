<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle($request, Closure $next, ...$permissions)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect('/login');
        }

        $user = Auth::guard('admin')->user();

       
        if (!in_array($user->permission, $permissions)) {
          
            switch ($user->permission) {
                case 1:
                    return redirect()->route('Dashboard.admin');
                case 2:
                    return redirect()->route('Dashboard.user');
                case 3:
                case 4:
                    return redirect()->route('Dashboard.employee');
                default:
                    return redirect('/login');
            }
        }

        return $next($request);
    }
}


