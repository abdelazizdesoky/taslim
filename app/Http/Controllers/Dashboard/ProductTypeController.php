<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Brand;
use App\Models\ProductType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductTypeController extends Controller
{
    public function index()
    {
        $productTypes = ProductType::with('brand')->get();
        return view('Dashboard.Admin.Product.product-types.index', compact('productTypes'));
    }

    public function create()
    {
        $brands = Brand::all();
        return view('Dashboard.Admin.Product.product-type.add', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required',
            'brand_id' => 'required'
        ]);

        ProductType::create($request->all());

        session()->flash('add');
        return redirect()->back();
    }

    public function show(ProductType $productType)
    {
        return view('Dashboard.Admin.Product.product-types.show', compact('productType'));
    }

    public function edit(ProductType $productType)
    {
        $brands = Brand::all();
        return view('Dashboard.Admin.Product.product-types.edit', compact('productType', 'brands'));
    }

    public function update(Request $request, ProductType $productType)
    {
        $request->validate([
            'type_name' => 'required',
            'brand_id' => 'required'
        ]);

        $productType->update($request->all());

        return redirect()->route('product-types.index');
    }

    public function destroy(ProductType $productType)
    {
        $productType->delete();

        return redirect()->route('product-types.index');
    }
}
