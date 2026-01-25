<?php

namespace App\Listeners;

use App\Models\CartItem;
use App\Models\Product;

class MergeCartAfterLogin
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        // CHỈ merge cart cho guard 'web' (khách hàng)
        // KHÔNG merge cho guard 'admin'
        if ($event->guard !== 'web') {
            return;
        }
        
        $user = $event->user;
        
        // Kiểm tra nếu user có role admin/staff thì không merge cart
        if ($user->role && in_array($user->role->name, ['admin', 'staff'])) {
            return;
        }
        
        // Merge cart từ session vào database
        $this->mergeSessionCartToDatabase($user);
    }
    
    /**
     * Merge cart từ session vào database
     */
    private function mergeSessionCartToDatabase($user): void
    {
        $sessionCart = session()->get('cart', []);
        
        if (empty($sessionCart)) {
            return;
        }
        
        foreach ($sessionCart as $productId => $item) {
            // Kiểm tra sản phẩm tồn tại
            $product = Product::find($productId);
            if (!$product) {
                continue;
            }
            
            // Kiểm tra nếu sản phẩm đã có trong cart của user
            $cartItem = CartItem::where('user_id', $user->id)
                              ->where('product_id', $productId)
                              ->first();
            
            if ($cartItem) {
                // Cập nhật số lượng
                $cartItem->quantity += $item['quantity'];
                
                // Kiểm tra số lượng tồn kho
                if ($cartItem->quantity > $product->stock) {
                    $cartItem->quantity = $product->stock;
                }
                
                // Cập nhật giá nếu cần
                if (isset($item['price'])) {
                    $cartItem->price = $item['price'];
                }
                
                $cartItem->save();
            } else {
                // Kiểm tra số lượng tồn kho trước khi thêm
                $quantity = $item['quantity'];
                if ($quantity > $product->stock) {
                    $quantity = $product->stock;
                }
                
                // Thêm mới vào cart
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $item['price'] ?? $product->price,
                    // Thêm các trường khác nếu cần: size, color, etc.
                ]);
            }
        }
        
        // Xóa cart từ session sau khi merge
        session()->forget('cart');
        
        // Cập nhật lại session cart count
        $cartCount = CartItem::where('user_id', $user->id)->sum('quantity');
        session()->put('cart_count', $cartCount);
        
        // Hoặc có thể dùng relationship count
        // session()->put('cart_count', $user->cartItems()->count());
    }
}