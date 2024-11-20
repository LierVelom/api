<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->desc,
                    'price' => $product->price,
                    'quantity' => $product->pivot->quantity,
                    'promotions' => $product->promotions->map(function ($promotion) {
                        return [
                            'id' => $promotion->id,
                            'name' => $promotion->name,
                            'description' => $promotion->description,
                            'discount_percentage' => $promotion->discount_percentage,
                            'discount_amount' => $promotion->discount_amount,
                            'start_date' => $promotion->start_date,
                            'end_date' => $promotion->end_date,
                        ];
                    })
                ];
            }),
            'created_at' => $this->created_at,
        ];
    }
}
