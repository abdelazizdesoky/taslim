<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller

{
    
    public function index()
    {
        $employees = Employee::all();
        return view('Dashboard.Admin.Employee.index',compact('employees'));
    }

    public function create()
    {
        return view('Dashboard.Admin.Employee.create');
      
    }


    public function store(request $request){

  

        try {

            $employees = new Employee();
            $employees->code = $request->code;
            $employees->password = Hash::make($request->password);
            $employees->name = $request->name;
            $employees->type = $request->type;//1-مندوب تسليم ---2 امين مخزن
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

            $employee = Employee::findorfail($request->id);
            $employee->code = $request->code;
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

    public function destroy(request $request)
    {
     
        Employee::destroy($request->id);
          session()->flash('delete');
          return redirect()->route('employee.index');
      }

    


    public function edit($id)
    {
       
        $employee = Employee::findorfail($id);
        return view('Dashboard.Admin.Employee.edit',compact('employee'));
    }


    public function update_password(request $request)
    {
        try {
            $Employee = Employee::findorfail($request->id);
            $Employee->update([
                'password'=>Hash::make($request->password)
            ]);

            session()->flash('edit');
            return redirect()->back();
        }

        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function update_status(request $request)
    {
       
   
     try {

            $Employee = Employee::findorfail($request->id);
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

}