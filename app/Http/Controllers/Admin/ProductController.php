<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpKernel\HttpCache\Store;

class ProductController extends Controller
{
    public function showFormAddProduct()
    {
        $categories = Category::all();
        return view('admin.pages.products-add', compact('categories'));
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $slug = Str::slug($request->name) . '-' . time();

        //create product
        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock ?? 0,
            'unit' => $request->unit ?? 'cái, hũ, túi',
            'status' => 'in_stock',
        ]);

        //handle images uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = "uploads/products/" . $imageName;

                $resizedImage = Image::make($image)->resize(600, 600)->encode();

                Storage::disk('public')->put($path, $resizedImage);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                ]);
            }
        }
        return redirect()->route('admin.products.add')->with('success', 'Thêm sản phẩm thành công.');
    }

    public function index()
    {
        $products = Product::with('category', 'images')->get();
        $categories = Category::all();
        return view('admin.pages.products', compact('products', 'categories'));
    }

    public function updateProduct(Request $request)
    {
        $request->validate([
            'id'          => 'required|exists:products,id',
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'unit'        => 'nullable|string|max:50',
            'images.*'    => 'image',
        ]);

        $product = Product::findOrFail($request->id);

        $product->update([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price'       => $request->price,
            'unit'        => $request->unit ?? 'cái',
            'stock'       => $request->stock ?? 0,
        ]);
        if ($request->hasFile('images')) {
            $oldImages = ProductImage::where('product_id', $product->id)->get();
            foreach ($oldImages as $image) {
                Storage::disk('public')->delete($image->image);
            }
            ProductImage::where('product_id', $product->id)->delete();

            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = 'uploads/products/' . $imageName;
                $resizedImage = Image::make($image)
                    ->resize(600, 600)
                    ->encode();
                Storage::disk('public')->put($path, $resizedImage);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $path,
                ]);
            }
        }
        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật sản phẩm thành công!',
            'data'    => [
                'id'            => $product->id,
                'name'          => $product->name,
                'slug'          => $product->slug,
                'category_name' => $product->category->name,
                'description'   => $product->description,
                'price'         => $product->price,
                'stock'         => $product->stock,
                'unit'          => $product->unit,
                'status'        => $product->status === 'in_stock' ? 'Còn hàng' : 'Hết hàng',
                'images'        => $product->images->map(fn($img) => asset('storage/' . $img->image)),
            ],
        ]);
    }
    public function deleteProduct(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->id);

        // Xóa ảnh trong storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
        }
        $product->images()->delete();

        // Xóa sản phẩm
        $product->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Xóa sản phẩm thành công!',
            'id'      => $request->id,
        ]);
    }
}
