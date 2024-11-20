<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
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
            'description' => $this->description,
            'discount_percentage' => $this->discount_percentage,
            'discount_amount' => $this->discount_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
