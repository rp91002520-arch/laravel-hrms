<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('auth');
        $this->productService = $productService;
    }

    public function index(Request $request)
{
    $query = Product::with('user'); // ✅ ADD THIS

    if(auth()->user()->role !== 'admin'){
        $query->where('user_id', auth()->id());
    }

    if($request->search){
        $query->where('name','like','%'.$request->search.'%');
    }

    $query->orderBy('id','desc');

    $products = $query->paginate(2)->withQueryString();

    return view('products.index', compact('products'));
}

    public function store(StoreProductRequest $request)
{
    try {

        $product = $this->productService->createProduct($request->all());
        $product->load('user'); 


        return new ProductResource($product);

    } catch (\Exception $e) {

        return response()->json([
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);

    }
}
   public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    // Authorization
    if (auth()->user()->role !== 'admin' && $product->user_id != auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    // Take all input except image
    $data = $request->except('image');

    // Handle image upload
    if ($request->hasFile('image')) {
        // Delete old image
        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }

        // Store new image and set relative path
        $imagePath = $request->file('image')->store('products', 'public');
        $data['image'] = $imagePath;
    }

    // Update product
    $product->update($data);
    $product->load('user'); // ✅ ADD THIS

    // Return updated product with correct image path
    return new ProductResource($product);
}

public function destroy($id)
{
    $product = Product::findOrFail($id);

    $product->delete();

    return response()->json([
        'message' => 'Product deleted successfully'
    ]);
}
public function deletedProducts()
{
    $products = Product::onlyTrashed()->get();

    return view('products.deleted', compact('products'));
}
public function restore($id)
{
    Product::withTrashed()->findOrFail($id)->restore();

    return redirect()->back()->with('success','Product restored');
}
public function forceDelete($id)
{
    Product::withTrashed()->findOrFail($id)->forceDelete();

    return redirect()->back()->with('success','Product permanently deleted');
}

}