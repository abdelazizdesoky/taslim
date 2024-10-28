<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\adminLoginRequest;

class AdminController extends Controller
{

    
    public function store(adminLoginRequest $request)
    {

    
    if ($request->authenticate()) {
        $request->session()->regenerate();
        
        $admin = Auth::guard('admin')->user();
        
        return match ($admin->permission) {
            
            1 => redirect()->intended(RouteServiceProvider::ADMIN),
            2 => redirect()->intended(RouteServiceProvider::HOME),
            3, 4=> redirect()->intended(RouteServiceProvider::EMPLOYEE),

            default => redirect()->back()
                ->withErrors(['name' => 'Invalid permissions'])
        };
    }

    return redirect()->back()
        ->withErrors(['name' => 'Invalid credentials']);
}
       
  

  

    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

   
}
