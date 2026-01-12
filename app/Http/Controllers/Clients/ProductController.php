<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::with('products')->get();
        $products = \App\Models\Product::with('firstImage')->where('status', 'in_stock')->paginate(9);
        foreach ($products as $product) {
            $product->image_url = $product->firstImage?->image
                ? asset('storage/uploads/products/' . $product->firstImage->image) : asset('storage/uploads/products/default-product.png');
        }

        return view('clients.pages.products', compact('categories', 'products'));
    }

    public function filter(Request $request)
    {
        $query = Product::query();
        // filter catrgory if exists
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
        // filter price if exists
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }
        // filter sort_by if exists
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;

                default:
                    $query->orderBy('id', 'desc');
                    break;
            }
        }

        $products = $query->paginate(9);

        foreach ($products as $product) {
            $product->image_url = $product->firstImage?->image
                ? asset('storage/uploads/products/' . $product->firstImage->image) : asset('storage/uploads/products/default-product.png');
        }

        return response()->json([
            'products' => view('clients.components.products-grid', compact('products'))->render(),
        ]);
    }
}
