<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCode;
use App\Models\ProductType;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    
    public function index()
    {
        $products = Product::with(['productType.brand', 'productCodes', 'productDetails'])
                           ->select('id', 'product_name', 'type_id') 
                           ->paginate(50); 
    
        return view('Dashboard.Admin.Product.index', compact('products'));
    }



         public function create()
         {

            $brands = Brand::all();
            $productTypes = ProductType::all();
            $products= Product::all();
          
             return view('Dashboard.Admin.Product.create', compact('brands','productTypes','products'));
         }





     
         public function store(request $request)
         {
            // قواعد الفاليديشن
            $validatedData = $request->validate([
                'product_name' => 'required|string|max:255',
                'type_id' => 'required|exists:product_types,id', // تأكد من أن type_id موجود في جدول product_types
                'detail_name' => 'required|string|max:255',
                'product_code' => 'required|string|max:255|unique:product_codes,product_code', // كود المنتج يجب أن يكون فريدًا
            ], [
                // رسائل الأخطاء المخصصة (اختياري)
                'product_name.required' => 'اسم المنتج مطلوب.',
                'type_id.required' => 'نوع المنتج مطلوب.',
                'type_id.exists' => 'نوع المنتج المحدد غير موجود.',
                'detail_name.required' => 'تفاصيل المنتج مطلوبة.',
                'product_code.required' => 'كود المنتج مطلوب.',
                'product_code.unique' => 'كود المنتج مستخدم بالفعل.',
            ]);

            DB::beginTransaction();

            try {
                // إنشاء المنتج وحفظه
                $products = new Product();
                $products->product_name = $validatedData['product_name'];
                $products->type_id = $validatedData['type_id'];
                $products->save();

                // إنشاء وحفظ تفاصيل المنتج
                $productDetail = new ProductDetail();
                $productDetail->detail_name = $validatedData['detail_name'];
                $productDetail->product_id = $products->id;
                $productDetail->save();

                // إنشاء وحفظ كود المنتج
                $productCode = new ProductCode();
                $productCode->product_code = $validatedData['product_code'];
                $productCode->product_id = $products->id;
                $productCode->save();

                DB::commit();
                session()->flash('add', 'تم إضافة المنتج بنجاح.');
                return redirect()->route('products.index');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }

         }


     
         public function edit($id)
         {

             $brands = Brand::all();
             $productTypes = ProductType::all();
             $product = Product::with(['productCodes', 'productDetails'])->findOrFail($id);

         return view('Dashboard.Admin.Product.edit', compact('brands', 'productTypes', 'product'));

         }
         
         public function update(Request $request)
         {

         
             DB::beginTransaction();
     
             try {
         
                $product = Product::findOrFail($request->id);
                $product->product_name = $request->product_name;
              
              
                $product->save();
    
                $productdetail = ProductDetail::findOrFail($product->id);
                $productdetail->detail_name = $request->detail_name;
                $productdetail->product_id = $product->id;
              
                $productdetail->save();
    
                $productCode = ProductCode::findOrFail($product->id);
                $productCode->product_code = $request->product_code;
                $productCode->product_id = $product->id;
              
                $productCode->save();
         
         
                DB::commit();
                session()->flash('edit');
                return redirect()->route('products.index');
    
    
           }
            catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
    
    
         }
     
     
         public function destroy(request $request)
         {
            Product::destroy($request->id);
            
             session()->flash('delete');
             return redirect()->back();
         }
     
     }
     
