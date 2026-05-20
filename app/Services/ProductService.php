<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductService
{

   public function createProduct($data)
{
    DB::beginTransaction();

    try {

        $data['user_id'] = auth()->id();

        if(isset($data['image'])){
            $data['image'] = $data['image']->store('products','public');
        }

        $product = Product::create($data);

        DB::commit();

        return $product;

    } catch (\Exception $e) {

        DB::rollBack();

        if(isset($data['image'])){
            Storage::disk('public')->delete($data['image']);
        }

        throw new \Exception("Product creation failed: ".$e->getMessage());
    }
}
}