<div class="ltn__utilize-menu-head">
    <span class="ltn__utilize-menu-title">Cart</span>
    <button class="ltn__utilize-close">×</button>
</div>
<div class="mini-cart-product-area ltn__scrollbar">
        @php
        $subtotal = 0;
    @endphp
    @if (!empty($cartItems) && count($cartItems) > 0)

    @foreach ($cartItems as $item)
    @php
        $product = Auth::check() ? $item->product : \App\Models\Product::find($item['product_id']);
        $quantity = Auth::check() ? $item->quantity : $item['quantity'];
        $subtotal  += $quantity * $product->price;
    @endphp
    <div class="mini-cart-item clearfix">
            <div class="mini-cart-img">
                <a href="javascript:void(0)">
                   <img src="{{ asset($product->firstImage->image_path ?? 'storage/uploads/products/default-product.png') }}" alt="Image">
                </a>
                <span class="mini-cart-item-delete" data-id="{{ $product->id }}">
                    <i class="icon-cancel"></i></span>
            </div>
            <div class="mini-cart-info">
                <h6><a href="#">{{ $product->name }}</a></h6>
                <span class="mini-cart-quantity">{{ $quantity }} x {{ number_format($product->price, 0,',','.') }}</span>
            </div>
        </div>
        
    @endforeach
        
    @else

    @endif

</div>
<div class="mini-cart-footer">
    <div class="mini-cart-sub-total">
        <h5> Tổng tiền: <span>{{ number_format($subtotal, 0,',','.') }} vnd</span></h5>
    </div>
    <div class="btn-wrapper">
        <a href="cart.html" class="theme-btn-1 btn btn-effect-1">Xem giỏ hàng</a>
        <a href="cart.html" class="theme-btn-2 btn btn-effect-2">Thanh toán</a>
    </div>

</div>

</div>
</div>
<!-- Utilize Cart Menu End -->
