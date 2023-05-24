<?php

namespace App\Http\Resources\Ecommerce\Sale;

use App\Http\Resources\Ecommerce\Sale\SaleOResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleOCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            "data" => SaleOResource::collection($this->collection),
        ];
    }
}