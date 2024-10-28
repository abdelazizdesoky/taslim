<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{

    public function index()
    {
        $admins = Admin::all();
        return view('Dashboard.Admin.admin.index',compact('admins'));
    }

    public function create()
    {
        return view('Dashboard.Admin.admin.create');
      
    }


    public function store(request $request){

  

        try {

            $employees = new Admin();
            $employees->email = $request->email;
            $employees->password = Hash::make($request->password);
            $employees->name = $request->name;
            $employees->permission = $request->permission;//--1 admin 2- user  3- deliver 4-store
            $employees->phone = $request->phone;
            $employees->status = 1;
            $employees->save();

           
            session()->flash('add');
            return redirect()->route('employee.index');

        }
        catch (\Exception $e) {
         
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }


    }

    public function update(request $request)
    {
       
        try {

            $employee = Admin::findorfail($request->id);
            $employee->email = $request->email;
            $employee->name = $request->name;
            $employee->type = $request->type;
            $employee->phone = $request->phone;
            $employee->status = $request->status;
            $employee->save();

           
            session()->flash('edit');
            return redirect()->route('employee.index');

        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

   
    


    public function edit($id)
    {
       
        $admin = Admin::findorfail($id);
        return view('Dashboard.Admin.admin.edit',compact('admin'));
    }


   



    public function update_status(request $request)
    {
       
   
     try {

            $Employee = Admin::findorfail($request->id);
            $Employee->update([
                'status'=>$request->status
            ]);

            session()->flash('edit');
            return redirect()->back();
        }

        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


 



    
    public function update_password (request $request)
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
