<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('Dashboard.Admin.User.index',compact('users'));
    }


    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('Dashboard.Admin.User.create');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        return redirect()->route('user.index');
    }

    public function edit($id)
    {
       
        $user = User::findorfail($id);
        return view('Dashboard.Admin.User.edit',compact('user'));
    }

    public function update(request $request)
    {
        try {

            $user = User::findorfail($request->id);
            $user->email = $request->email;
            $user->name = $request->name;
        
            $user->save();

           
            session()->flash('edit');
            return redirect()->route('user.index');

        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    
    public function update_password(request $request)
    {
        try {
            $user = User::findorfail($request->id);
            $user->update([
                'password'=>Hash::make($request->password)
            ]);

            session()->flash('edit');
            return redirect()->back();
        }

        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(request $request)
    {
     
        User::destroy($request->id);
          session()->flash('delete');
          return redirect()->route('user.index');
      }
}
