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

     // إضافة التحقق على المدخلات
     $request->validate([
        'email' => 'required|email|unique:admins,email',
        'password' => 'required|min:8',
        'name' => 'required|string|max:255',
        'permission' => 'required|in:1,2,3,4',
    ], [
        'email.required' => 'البريد الإلكتروني مطلوب.',
        'email.email' => 'يجب أن يكون البريد الإلكتروني صالحًا.',
        'email.unique' => 'البريد الإلكتروني مسجل بالفعل.',
        'password.required' => 'كلمة المرور مطلوبة.',
        'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
        'name.required' => 'الاسم مطلوب.',
        'permission.required' => 'الرجاء تحديد نوع الصلاحية.',
        'permission.in' => 'نوع الصلاحية غير صالح.',
    ]);

        try {

            $admin = new Admin();
            $admin->email = $request->email;
            $admin->password = Hash::make($request->password);
            $admin->name = $request->name;
            $admin->permission = $request->permission;//--1 admin 2- user  3- deliver 4-store
            $admin->status = 1;
            $admin->save();

           
            session()->flash('add');
            return redirect()->route('admin.admins.index');

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



    public function update(request $request)
    {
        $request->validate([
           
           
            'name' => 'required|string|max:255',
            'permission' => 'required|in:1,2,3,4',
            'status' => 'required|in:1,2',
        ], [
          
            'name.required' => 'الاسم مطلوب.',
            'permission.required' => 'الرجاء تحديد نوع الصلاحية.',
            'permission.in' => 'نوع الصلاحية غير صالح.',
            'status.required' => 'الحالة مطلوبة.',
            'status.in' => 'يجب أن تكون الحالة صحيحة   .',
        ]);
      
    
       
        try {

            $admin = Admin::findorfail($request->id);
            $admin->name = $request->name;
            $admin->permission = $request->permission;
            $admin->status = $request->status;
            $admin->save();

           
            session()->flash('edit');
            return redirect()->route('admin.admins.index');

        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }


    
    public function update_password (request $request)
    {


        $request->validate([
           
          'password' => 'required|min:8',
        ], [
          
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
        ]);
        
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
