<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PromotionResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->desc,
            'price' => $this->price,
            'size' => $this->size,
            'color' => $this->color,
            'created_at' => $this->created_at,
            'promotions' => PromotionResource::collection($this->whenLoaded('promotions')), // Thêm khuyến mãi
        ];
    }
}
