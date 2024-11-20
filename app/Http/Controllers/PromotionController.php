<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Lấy danh sách khuyến mãi
     */
    public function index()
    {
        $promotions = Promotion::with('products')->get();
        return response()->json($promotions, 200);
    }

    /**
     * Tạo khuyến mãi mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $promotion = Promotion::create($validated);

        return response()->json([
            'message' => 'Promotion created successfully',
            'promotion' => $promotion,
        ], 201);
    }

    /**
     * Xem chi tiết khuyến mãi
     */
    public function show($id)
    {
        $promotion = Promotion::with('products')->find($id);

        if (!$promotion) {
            return response()->json(['message' => 'Promotion not found'], 404);
        }

        return response()->json($promotion, 200);
    }

    /**
     * Cập nhật khuyến mãi
     */
    public function update(Request $request, $id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return response()->json(['message' => 'Promotion not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $promotion->update($validated);

        return response()->json([
            'message' => 'Promotion updated successfully',
            'promotion' => $promotion,
        ], 200);
    }

    /**
     * Xóa khuyến mãi
     */
    public function destroy($id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return response()->json(['message' => 'Promotion not found'], 404);
        }

        $promotion->delete();

        return response()->json(['message' => 'Promotion deleted successfully'], 200);
    }

    /**
     * Gắn sản phẩm vào khuyến mãi
     */
    public function attachProduct(Request $request, $id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return response()->json(['message' => 'Promotion not found'], 404);
        }

        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $promotion->products()->attach($validated['product_ids']);

        return response()->json(['message' => 'Products attached to promotion successfully'], 200);
    }

    /**
     * Bỏ gắn sản phẩm khỏi khuyến mãi
     */
    public function detachProduct(Request $request, $id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return response()->json(['message' => 'Promotion not found'], 404);
        }

        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $promotion->products()->detach($validated['product_ids']);

        return response()->json(['message' => 'Products detached from promotion successfully'], 200);
    }
}
