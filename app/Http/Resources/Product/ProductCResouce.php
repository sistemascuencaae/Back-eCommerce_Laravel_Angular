<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->resource->id,
            "tittle" => $this->resource->tittle,
            "categorie_id" => $this->resource->categorie_id,
            "categorie" => [
                "id" => $this->resource->categorie->id,
                "icono" => $this->resource->categorie->icono,
                "name" => $this->resource->categorie->name,
            ],
            "slug" => $this->resource->slug,
            "sku" => $this->resource->sku,
            "tags" => $this->resource->tags,
            "tags_a" => $this->resource->tags ? explode(",", $this->resource->tags) : [],
            //Convertir el string en un array 
            "price_soles" => $this->resource->price_soles,
            "price_usd" => $this->resource->price_usd,
            "resumen" => $this->resource->resumen,
            "description" => $this->resource->description,
            "state" => $this->resource->state,
            "imagen" => env("APP_URL") . "/storage/app/" . $this->resource->imagen,
            // http://192.168.1.105/Back-eCommerce_Laravel_Angular/storage/app/productos/f0HbE16dLVAVvvjR9lXPpABIr3ROl3YHb9NAu7vp.png
            "stock" => $this->resource->stock,
            "checked_inventario" => $this->resource->type_inventario,

            "images" => $this->resource->images->map(function ($img) {
                return [
                    "id" => $img->id,
                    "file_name" => $img->file_name,
                    "imagen" => env("APP_URL") . "storage/app/" . $img->imagen,
                    "size" => $img->size,
                    "type" => $img->type,
                ];
            }),

            "sizes" => $this->resource->sizes
                ->map(function ($size) {
                    return [
                        "id" => $size->id,
                        "name" => $size->name,
                        "variaciones" => $size->product_size_colors
                            ->map(function ($var) {
                                        return [
                                            "id" => $var->id,
                                            "product_color_id" => $var->product_color_id,
                                            "product_color" => $var->product_color,
                                            "stock" => $var->stock,
                                        ];
                                    }),
                        "total" => $size->product_size_colors->sum("stock"),
                    ];
                }),
        ];
    }
}