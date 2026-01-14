<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addtoCart(Request $request)
    {
        $request->merge(['quantity' => (int)$request->quantity]);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);


        $product = Product::findOrFail($request->product_id);

        if (Auth::check()) {
            // User đã login
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($cartItem) {
                // Check tổng quantity sau khi cộng
                $newQuantity = $cartItem->quantity + $request->quantity;
                if ($newQuantity > $product->stock) {
                    return response()->json([
                        'message' => 'Số lượng vượt quá tồn kho. Tồn kho hiện có: ' . $product->stock .
                            '. Bạn đã có ' . $cartItem->quantity . ' trong giỏ hàng.'
                    ], 400);
                }
                $cartItem->quantity = $newQuantity;
                $cartItem->save();
            } else {
                // Check quantity ban đầu
                if ($request->quantity > $product->stock) {
                    return response()->json([
                        'message' => 'Số lượng vượt quá tồn kho. Tồn kho: ' . $product->stock
                    ], 400);
                }
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity
                ]);
            }

            $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity'); // Hoặc count() tùy ý
        } else {
            // User chưa login (session)
            $cart = session()->get('cart', []);

            if (isset($cart[$request->product_id])) {
                $newQuantity = $cart[$request->product_id]['quantity'] + $request->quantity;
                if ($newQuantity > $product->stock) {
                    return response()->json([
                        'message' => 'Số lượng vượt quá tồn kho. Tồn kho hiện có: ' . $product->stock .
                            '. Bạn đã có ' . $cart[$request->product_id]['quantity'] . ' trong giỏ hàng.'
                    ], 400);
                }
                $cart[$request->product_id]['quantity'] = $newQuantity;
            } else {
                // Check quantity ban đầu
                if ($request->quantity > $product->stock) {
                    return response()->json([
                        'message' => 'Số lượng vượt quá tồn kho. Tồn kho: ' . $product->stock
                    ], 400);
                }
                $cart[$request->product_id] = [
                    'product_id' => $request->product_id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $request->quantity,
                    'stock' => $product->stock,
                    'image' => $product->images->first()->image ?? 'uploads/products/default-product.png'
                ];
            }

            session()->put('cart', $cart);
            $cartCount = count($cart);
        }

        return response()->json([
            'message' => true   ,
            'cart_count' => $cartCount
        ]);
    }
public function loadMiniCart()
{
    $cartItems = [];
    $subtotal = 0;

    if (Auth::check()) {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();
    } else {
        $cartItems = session('cart', []);

    }
    
    return response()->json([
        'status' => true,
        'html' => view('clients.components.includes.mini_cart', compact('cartItems'))->render()
    ]);
}

public function removeFromMiniCart(Request $request)
{
    $request->validate([
        'product_id' => 'required']);

    if (Auth::check()) {
        CartItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->delete();
        $cartCount = CartItem::where('user_id', Auth::id())->count();
    } else {
        $cart = session()->get('cart', []);
        unset($cart[$request->product_id]);
            session()->put('cart', $cart);
            $cartCount = count($cart);
    }

    return response()->json([
        'status' => true,
        'cart_count' => $cartCount
    ]);
}
}