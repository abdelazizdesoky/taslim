<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function index()
    {
        $Suppliers = Supplier::all();
        return view('Dashboard.Admin.Supplier.index',compact('Suppliers'));
    }

    public function create()
    {
        return view('Dashboard.Admin.Supplier.create');
    }

    public function store(request $request)
    {

    try {

       $Supplier = new Supplier();
       $Supplier->code = $request->code;
       $Supplier->name = $request->name;
       $Supplier->address = $request->address;
       $Supplier->phone = $request->phone;
       $Supplier->status = $request->status;
     
       $Supplier->save();


      session()->flash('add');
      return redirect()->route('admin.supplier.index');

        }

        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $Supplier = Supplier::findorfail($id);
        return view('Dashboard.Admin.Supplier.edit',compact('Supplier'));
    }

    public function update(request $request)
    {
        

        $Supplier = Supplier::findOrFail($request->id);

        $Supplier->update($request->all());

        $Supplier->save();

        session()->flash('edit');
        return redirect()->route('admin.supplier.index');
    }



    public function destroy(request $request)
    {
        Supplier::destroy($request->id);
        session()->flash('delete');
        return redirect()->back();
    }

}
