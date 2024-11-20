<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Ví dụ: ?category_id[eq]=1 để lọc category_id bằng 1
        if ($request->has('category_id')) {
            $this->applyFilter($query, 'category_id', $request->category_id);
        }

        // Ví dụ: ?price[gt]=100&price[lt]=500 để lọc price > 100 và price < 500
        if ($request->has('price')) {
            $this->applyFilter($query, 'price', $request->price);
        }

        // Tương tự cho các trường khác
        if ($request->has('size')) {
            $query->where('size', $request->size); // Bộ lọc đơn giản (bằng)
        }

        if ($request->has('color')) {
            $query->where('color', $request->color); // Bộ lọc đơn giản (bằng)
        }

        $products = $query->with('promotions')->get();

        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $product->load('promotions');
        return new ProductResource($product);  // Trả về một sản phẩm
    }

    public function relatedProducts($id)
    {
        $product = Product::findOrFail($id);

        // Lấy tên của sản phẩm hiện tại làm từ khóa
        $search = $product->name;

        // Khởi tạo query để tìm sản phẩm liên quan theo category_id và tên, mô tả hoặc chuyên mục
        $query = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) // Bỏ qua sản phẩm hiện tại
            ->limit(10) // Giới hạn số lượng sản phẩm liên quan
            ->with('promotions'); // Gắn quan hệ khuyến mãi vào sản phẩm

        // Lọc sản phẩm theo tên, mô tả và chuyên mục với từ khóa là tên của sản phẩm hiện tại
        $query->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%') // Lọc theo tên sản phẩm
                ->orWhere('desc', 'like', '%' . $search . '%') // Lọc theo mô tả sản phẩm
                ->orWhereHas('categories', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%'); // Lọc theo tên chuyên mục
                });
        });

        // Lấy các sản phẩm liên quan với các bộ lọc tìm kiếm
        $relatedProducts = $query->get();

        return ProductResource::collection($relatedProducts);
    }


    public function productsWithPromotions()
    {
        $products = Product::whereHas('promotions') // Chỉ lấy sản phẩm có khuyến mãi
            ->with('promotions') // Gắn quan hệ khuyến mãi vào sản phẩm
            ->get();

        return response()->json($products, 200);
    }

    /**
     * Áp dụng filter tùy chỉnh với các toán tử.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $field
     * @param  array  $conditions
     */
    protected function applyFilter($query, $field, $conditions)
    {
        foreach ($conditions as $operator => $value) {
            switch ($operator) {
                case 'eq': // bằng
                    $query->where($field, '=', $value);
                    break;
                case 'ne': // không bằng
                    $query->where($field, '!=', $value);
                    break;
                case 'gt': // lớn hơn
                    $query->where($field, '>', $value);
                    break;
                case 'lt': // nhỏ hơn
                    $query->where($field, '<', $value);
                    break;
                case 'gte': // lớn hơn hoặc bằng
                    $query->where($field, '>=', $value);
                    break;
                case 'lte': // nhỏ hơn hoặc bằng
                    $query->where($field, '<=', $value);
                    break;
            }
        }
    }
}
