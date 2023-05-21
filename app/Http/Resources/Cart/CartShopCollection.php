<?php

namespace App\Http\Resources\Cart;

use App\Http\Resources\Cart\CartShopResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartShopCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            "data" => CartShopResource::collection($this->collection),
        ];
    }
}