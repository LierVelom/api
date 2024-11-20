<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        // Validate request
        $request->validate([
            'payment_method' => 'required|string', // Example: 'credit_card', 'cash'
        ]);

        $user = Auth::user();

        // Get the cart
        $cart = Cart::where('user_id', $user->id)->where('status', 'active')->with('products.promotions')->first();

        if (!$cart) {
            return response()->json([
                'message' => 'Cart not found',
            ], 404);
        }

        // Calculate total amount
        $totalAmount = $cart->products->reduce(function ($carry, $product) {
            // Lấy giá trị khuyến mãi cao nhất (nếu có)
            $maxDiscount = $product->promotions->reduce(function ($max, $promotion) {
                // Giả định khuyến mãi là giảm giá theo phần trăm
                $discount = $promotion->discount ?? 0;
                return max($max, $discount);
            }, 0);

            // Tính giá trị sau khuyến mãi
            $priceAfterDiscount = $product->price * (1 - $maxDiscount / 100);

            // Tính tổng cộng
            return $carry + ($priceAfterDiscount * $product->pivot->quantity);
        }, 0);

        // Simulate payment success
        $isPaymentSuccessful = true; // Mocked response

        if ($isPaymentSuccessful) {
            // Create an invoice
            $invoice = Invoice::create([
                'cart_id' => $cart->id,
                'voucher' => null, // Assuming no voucher applied
                'status' => 'success',
                'amount' => $totalAmount,
            ]);

            // Update cart status
            $cart->update(['status' => 'paid']);

            return response()->json([
                'message' => 'Payment successful',
                'invoice_id' => $invoice->id,
                'amount' => $totalAmount,
            ], 200);
        }

        // Handle payment failure
        return response()->json([
            'message' => 'Payment failed',
        ], 400);
    }
}
