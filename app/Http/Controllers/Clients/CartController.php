<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\Product;
use Attribute;
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
            'message' => true,
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
            'product_id' => 'required'
        ]);

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


    public function viewCart()
    {
        if (Auth::check()) {
            //lấy giỏ hàng từ db
            $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get()->map(function ($item) {
                return [
                    'product_id' => $item->product->id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'stock' => $item->product->stock,
                    'image' => optional(optional($item->product->image)->first())->image
                        ?? 'uploads/products/default-product.png',

                ];
            })->toArray();
        } else {
            //lấy giỏ hàng từ session
            $cartItems = session()->get('cart', []);
        }

        return view('clients.pages.cart', compact('cartItems'));
    }


    public function updateCart(Request $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity;

        if (Auth::check()) {
            // Cập nhật cart trong db
            $cartItem = CartItem::where('user_id', Auth::id())->where('product_id', $productId)->first();

            if (!$cartItem) {
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
            }

            $product = Product::find($productId);

            if ($quantity > $product->stock) {
                return response()->json(['error' => 'Số lượng vượt quá tồn kho'], 400);
            }

            $cartItem->quantity = $quantity;
            $cartItem->save();
        } else {
            // Cập nhật cart session
            $cart = session()->get('cart', []);

            if (!isset($cart[$productId])) {
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
            }

            $product = Product::find($productId);

            if ($quantity > $product->stock) {
                return response()->json(['error' => 'Số lượng vượt quá tồn kho'], 400);
            }

            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        // Tính toán lại tổng giỏ hàng
        $subtotal = $quantity * $product->price;
        $total = $this->calculaterCartTotal();
        $grandTotal = $total + 25000;

        return response()->json([
            'quantity' => $quantity,
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'total' => number_format($total, 0, ',', '.'),
            'grandTotal' => number_format($grandTotal, 0, ',', '.'),
        ]);
    }

    //handle remove cartIteam 
    public function removeCartItem(Request $request)
    {
        $productId = $request->product_id;

        if (Auth::check()) {
            // Cập nhật cart trong db
            CartItem::where('user_id', Auth::id())->where('product_id', $productId)->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        // Tính toán lại tổng giỏ hàng
        $total = $this->calculaterCartTotal();
        $grandTotal = $total + 25000;

        return response()->json([
            'total' => number_format($total, 0, ',', '.'),
            'grandTotal' => number_format($grandTotal, 0, ',', '.'),
        ]);
    }

    // tính tổng số tiền 
    private function calculaterCartTotal()
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())
                ->with('product')
                ->get()
                ->sum(fn($item) => $item->quantity * $item->product->price);
        } else {
            $cart = session()->get('cart', []);
            return collect($cart)->sum(
                fn($item) => $item['quantity'] * $item['price']
            );
        }
    }
}
