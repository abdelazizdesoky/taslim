<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('Dashboard.Admin.Product.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('Dashboard.Admin.Product.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required'
        ]);

        Brand::create($request->all());

        session()->flash('add');
        return redirect()->back();
    }

    public function show(Brand $brand)
    {
        return view('Dashboard.Admin.Product.brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('Dashboard.Admin.Product.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'brand_name' => 'required'
        ]);

        $brand->update($request->all());

        session()->flash('edit');
        return redirect()->route('brands.index');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        session()->flash('delete');
        return redirect()->route('brands.index');
    }
}
