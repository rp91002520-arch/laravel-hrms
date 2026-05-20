<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        dd($request->all());
        $request->validate([
    'id' => 'required|integer'
]);
        $products = [
            1 => ['name' => 'Laptop', 'price' => 50000],
            2 => ['name' => 'Phone', 'price' => 20000],
            3 => ['name' => 'Headphones', 'price' => 2000],
        ];

        $id = $request->id;

        $cart = session()->get('cart', []);

        // If item exists → increase quantity
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $products[$id]['name'],
                "price" => $products[$id]['price'],
                "quantity" => 1
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function index()
    {
        $cart = session()->get('cart', []);

        return view('cart', compact('cart'));
    }
}
