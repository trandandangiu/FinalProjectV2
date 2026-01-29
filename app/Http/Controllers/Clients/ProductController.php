<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::with('products')->get();
        $products = \App\Models\Product::with('firstImage')->where('status', 'in_stock')->paginate(9);
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

        return response()->json([
            'products' => view('clients.components.products-grid', compact('products'))->render(),
            'pagination' => $products->links('clients.components.pagination.pagination_custom')->toHtml(),

        ]);
    }
    public function detail($slug)
    {
        $product = Product::with(['category', 'images','reviews.user'])->where('slug', $slug)->first();

        //get product in the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get();

        //calculater average
        $averageRating = round($product->reviews()->avg('rating') ?? 0, 1);
        // dd($averageRating);
        $hasPurchased = false;
        $hasReviewed = false;

        if (Auth::check()) {
            $user = Auth::user();

            $hasPurchased = OrderItem::whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', 'completed');
            })->where('product_id', $product->id)->exists();

            $hasReviewed = Review::where('user_id', $user->id)->where('product_id', $product->id)->exists();
        }


        return view('clients.pages.product-detail', compact('product', 'relatedProducts', 'hasPurchased', 'hasReviewed', 'averageRating'));
    }
}
