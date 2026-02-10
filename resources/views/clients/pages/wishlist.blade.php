@extends('layouts.client')

@section('title', 'Yêu thích')

@section('breadcrumb', 'Yêu thích')

@section('content')
    <!-- SHOPING CART AREA START -->
    <div class="liton__shoping-cart-area mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping-cart-inner">
                        <div class="shoping-cart-table table-responsive">
                            <table class="table">

                                <tbody>
                                    @forelse ($wishlists as $item)
                                        <tr>
                                            <td class="wishlist-product-remove" data-id="{{ $item->product->id }}">x</td>
                                            <td class="wishlist-product-image">
                                                <a href="{{ route('products.detail', $item->product->slug) }}">
                                                    <img src="{{ $item->product->image_url }}" alt="Sản phẩm">
                                                </a>
                                            </td>
                                            <td class="wishlist-product-info">
                                                <h4>
                                                    <a href="{{ route('products.detail', $item->product->slug) }}">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h4>
                                            </td>
                                            <td class="wishlist-product-price">
                                                <span>{{ number_format($item->product->price, 0, ',', '.') }} đ</span>
                                            </td>
                                            <td class="wishlist-product-stock">
                                                {{ $item->product->status == 'in_stock' ? 'còn hàng' : 'hết hàng' }}
                                            </td>
                                            <td class="hello"> 
                                                <a href="#" 
                                                    title="Thêm vào giỏ hàng" data-id="{{ $item->product->id }}">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    <span>THÊM VÀO GIỎ HÀNG</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center" style="color:#fff;">Danh sách yêu thích của bạn đang trống
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SHOPING CART AREA END -->
        <link rel="stylesheet" href="{{ asset('assets/clients/css/clients/wishlist.css') }}">
@endsection
