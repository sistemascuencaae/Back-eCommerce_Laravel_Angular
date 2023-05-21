<?php

namespace App\Http\Resources\Ecommerce\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductEResource extends JsonResource
{
    public function toArray($request)
    {
        // logica de descuento
        return [
            "id" => $this->id,
            "tittle" => $this->resource->tittle,
            "categorie_id" => $this->resource->categorie_id,
            "categorie" => [
                "id" => $this->resource->categorie->id,
                "icono" => $this->resource->categorie->icono,
                "name" => $this->resource->categorie->name,
            ],
            "slug" => $this->resource->slug,
            "sku" => $this->resource->sku,
            "price_soles" => $this->resource->price_soles,
            "price_usd" => $this->resource->price_usd,
            "resumen" => $this->resource->resumen,
            "description" => $this->resource->description,
            "imagen" => env("APP_URL") . "/storage/app/" . $this->resource->imagen,
            "stock" => $this->resource->stock,
            "checked_inventario" => $this->resource->type_inventario,
            "images" => $this->resource->images->map(function ($img) {
                return [
                    "id" => $img->id,
                    "file_name" => $img->file_name,
                    "imagen" => env("APP_URL") . "/storage/app/" . $img->imagen,
                    "size" => $img->size,
                    "type" => $img->type,
                ];
            }),
            "sizes" => $this->resource->sizes->map(function ($size) {
                return [
                    "id" => $size->id,
                    "name" => $size->name,
                    "total" => $size->product_size_colors->sum("stock"),
                    "variaciones" => $size->product_size_colors->map(function ($var) {
                            return [
                                "id" => $var->id,
                                "product_color_id" => $var->product_color_id,
                                "product_color" => $var->product_color,
                                "stock" => $var->stock,
                            ];
                        }),
                ];
            }),
        ];
    }
}