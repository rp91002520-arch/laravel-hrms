<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $products = Product::paginate(10);

        return view('admin.products.index',compact('products'));
    }

    public function store(ProductRequest $request)
    {
        $product = $this->service->createProduct($request->all());

        return response()->json([
            'status'=>true,
            'data'=>$product
        ]);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
    }
    
}