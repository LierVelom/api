<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Thêm sản phẩm vào giỏ hàng
    public function addProduct(Request $request)
    {
        $user = Auth::user();
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        // Kiểm tra nếu giỏ hàng của người dùng hiện tại đã tồn tại, nếu chưa, tạo mới
        $cart = Cart::firstOrCreate(['user_id' => $user->id, 'status' => 'active']);

        // Kiểm tra nếu sản phẩm đã có trong giỏ hàng
        if ($cart->products()->where('product_id', $productId)->exists()) {
            return response()->json(['message' => 'Product already in cart'], 400);
        }

        // Thêm sản phẩm vào giỏ hàng với số lượng
        $cart->products()->attach($productId, ['quantity' => $quantity]);

        return response()->json(['message' => 'Product added to cart'], 201);
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateProduct(Request $request, $productId)
    {
        $user = Auth::user();
        $quantity = $request->input('quantity');

        // Kiểm tra quantity có hợp lệ hoặc tồn tại
        if (!is_numeric($quantity) || $quantity <= 0) {
            return response()->json(['message' => 'Invalid quantity'], 400);
        }
        // Tìm giỏ hàng ở trạng thái 'active' của người dùng hiện tại
        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'active') // Điều kiện trạng thái active
            ->firstOrFail();

        // Kiểm tra nếu sản phẩm có trong giỏ hàng
        if (!$cart->products()->where('product_id', $productId)->exists()) {
            return response()->json(['message' => 'Product not found in cart'], 404);
        }

        // Cập nhật số lượng sản phẩm
        $cart->products()->updateExistingPivot($productId, ['quantity' => $quantity]);

        // Cập nhật updated_at của giỏ hàng
        $cart->touch();

        return response()->json(['message' => 'Product quantity updated'], 200);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeProduct($productId)
    {
        $user = Auth::user();
        // Tìm giỏ hàng ở trạng thái 'active' của người dùng hiện tại
        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'active') // Điều kiện trạng thái active
            ->firstOrFail();

        // Kiểm tra nếu sản phẩm có trong giỏ hàng
        if (!$cart->products()->where('product_id', $productId)->exists()) {
            return response()->json(['message' => 'Product not found in cart'], 404);
        }

        // Xóa sản phẩm khỏi giỏ hàng
        $cart->products()->detach($productId);

        return response()->json(['message' => 'Product removed from cart'], 200);
    }

    // Lấy chi tiết giỏ hàng
    public function show()
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['message' => 'User not logged in'], 401);
        }

        // Lấy giỏ hàng nếu đã tồn tại, hoặc tạo mới nếu chưa có
        $cart = Cart::with('products')->firstOrCreate(
            ['user_id' => $userId, 'status' => 'active'], // Điều kiện tìm kiếm
            ['created_at' => now()] // Dữ liệu mặc định để tạo mới nếu không tìm thấy
        );

        return new CartResource($cart);
    }
}
