<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\InvoiceResource;

class InvoiceController extends Controller
{
    // Retrieve a list of invoices
    public function index()
    {
        $invoices = Invoice::whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return InvoiceResource::collection($invoices);
    }

    // Retrieve details of a specific invoice
    public function show($id)
    {
        $invoice = Invoice::with('cart.products')->whereHas('cart', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        return new InvoiceResource($invoice);
    }

    // Create an invoice
    public function store(Request $request)
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)
            ->where('status', 'active')
            ->firstOrFail();

        $totalAmount = $cart->products->reduce(function ($carry, $product) {
            $discountedPrice = $product->price;

            if ($product->pivot->promotion_id) {
                $promotion = $product->pivot->promotion;
                if ($promotion && $promotion->discount_type === 'percentage') {
                    $discountedPrice -= $product->price * ($promotion->discount_value / 100);
                } elseif ($promotion && $promotion->discount_type === 'fixed') {
                    $discountedPrice -= $promotion->discount_value;
                }
            }

            return $carry + ($discountedPrice * $product->pivot->quantity);
        }, 0);

        $invoice = Invoice::create([
            'cart_id' => $cart->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        // Update cart status to inactive
        $cart->update(['status' => 'inactive']);

        return new InvoiceResource($invoice);
    }

    // Update an invoice (optional, typically not used in production)
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->only(['status']));

        return new InvoiceResource($invoice);
    }

    // Delete an invoice (optional, typically not used in production)
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return response()->json(['message' => 'Invoice deleted successfully']);
    }
}
