<?php

namespace App\Http\Resources\Discount;

use App\Http\Resources\Discount\DiscountResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DiscountCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "data" => DiscountResource::collection($this->collection),
        ];
    }
}