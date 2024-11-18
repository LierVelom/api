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

        $products = $query->get();

        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);  // Trả về một sản phẩm
    }

    public function relatedProducts($id)
    {
        $product = Product::findOrFail($id);

        // Lấy sản phẩm liên quan theo category_id
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) // Bỏ qua sản phẩm hiện tại
            ->orWhere('name', 'like', '%' . $product->name . '%') // Lọc theo tên sản phẩm
            ->orWhere('desc', 'like', '%' . $product->desc . '%') // Lọc theo mô tả sản phẩm
            ->orWhere('category_id', $product->category_id) // Lọc theo category_id
            ->limit(10) // Giới hạn số lượng sản phẩm liên quan
            ->get();

        return ProductResource::collection($relatedProducts);
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
