<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
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
            3 => redirect()->intended(RouteServiceProvider::EMPLOYEE),
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

    public function edit($id)
    {
       
        $admin = Admin::findorfail($id);
        return view('Dashboard.Admin.admin.edit',compact('admin'));
    }

    public function update(request $request)
    {
        try {

            $admin = Admin::findorfail($request->id);
            $admin->email = $request->email;
            $admin->name = $request->name;
        
            $admin->save();

           
            session()->flash('edit');
            return redirect()->back();

        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    
    public function update_password(request $request)
    {
        try {
            $admin = Admin::findorfail($request->id);
            $admin->update([

                'password'=>Hash::make($request->password)
            ]);

            session()->flash('edit');
            return redirect()->back();
        }

        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function delete(request $request)
    {
     
        Admin::destroy($request->id);
          session()->flash('delete');
          return redirect()->back();
      }
}
