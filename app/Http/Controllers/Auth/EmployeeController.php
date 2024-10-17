<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\EmployeeLoginRequest;


class EmployeeController extends Controller

{
    
    public function store(EmployeeLoginRequest $request)

    {
        if ($request->authenticate()){

          $request->session()->regenerate();

          return redirect()->intended(RouteServiceProvider::EMPLOYEE);

         }else{

          return redirect()->back()->withErrors([' كود او باسورد خطأ']);
      }
        

       
    }

    
    public function destroy(Request $request)
    {
        Auth::guard('employee')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}