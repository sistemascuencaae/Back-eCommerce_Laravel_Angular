<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ecommerce\Product\ProductEResource;
use App\Models\Models\Product\Categorie;
use App\Models\Models\Product\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {

        $sliders = Slider::orderBy("id", "desc")->get();

        $categories = Categorie::orderBy("id", "desc")->take(4)->get();

        $group_categories_product = collect([]);
        // dd($categories);
        foreach ($categories as $key => $categorie) {
            $products = $categorie->products->take(3);
            $group_categories_product->push([
                "id" => $categorie->id,
                "name" => $categorie->name,
                "products" => $products->map(function ($product) {
                    return [
                        "id" => $product->id,
                        "tittle" => $product->tittle,
                        "slug" => $product->slug,
                        "price_soles" => $product->price_soles,
                        "price_usd" => $product->price_usd,
                        "imagen" => env("APP_URL") . "/storage/app/" . $product->imagen,
                    ];
                }),
            ]);
        }

        $products_aletorio_a = Product::inRandomOrder()->limit(4)->get();

        $products_aletorio_b = Product::inRandomOrder()->limit(8)->get();

        return response()->json([
            "sliders" => $sliders->map(function ($slider) {
                return [
                    "id" => $slider->id,
                    "url" => $slider->url,
                    "name" => $slider->name,
                    "imagen" => env("APP_URL") . "/storage/app/" . $slider->imagen,
                ];
            }),
            "group_categories_product" => $group_categories_product,
            "products_aletorio_a" => $products_aletorio_a->map(function ($product) {
                return ProductEResource::make($product);
            }),
            "products_aletorio_b" => $products_aletorio_b->map(function ($product) {
                return ProductEResource::make($product);
            }),
        ]);

    }

    public function detail_product($slug_product)
    {
        $product = Product::where("slug", $slug_product)->first();
        if (!$product) {
            return response()->json(["message" => 403]);
        }
        $product_relateds = Product::where("id", "<>", $product->id)->where("categorie_id", $product->categorie_id)->orderBy("id", "asc")->get();
        return response()->json([
            "message" => 200,
            "product_detail" => ProductEResource::make($product),
            "product_relateds" => $product_relateds->map(function ($product) {
                return ProductEResource::make($product);
            })
        ]);
    }

}