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
                        "imagen" => env("APP_URL") . "storage/app/" . $product->imagen,
                        "reviews_count" => $product->reviews_count,
                        "avg_rating" => round($product->avg_rating),
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
                    "imagen" => env("APP_URL") . "storage/app/" . $slider->imagen,
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

// public function home()
// {

//     $sliders = Slider::orderBy("id","desc")->get();

//     $categories = Categorie::withCount("products")->having("products_count",">",0)->orderBy("id","desc")->take(4)->get();

//     $group_categories_product = collect([]);
//     // dd($categories);
//     foreach ($categories as $key => $categorie) {
//         $products = $categorie->products->take(3);
//         $group_categories_product->push([
//             "id" => $categorie->id,
//             "name" => $categorie->name,
//             "products" => $products->map(function($product) {
//                 $discount_g = null;
//                 if($product->discount_p && $product->discount_c){
//                     $discount_g =$product->discount_p;
//                 }else{
//                     if($product->discount_p && !$product->discount_c){
//                         $discount_g =$product->discount_p;
//                     }else{
//                         if(!$product->discount_p && $product->discount_c){
//                             $discount_g =$product->discount_c;
//                         }
//                     }
//                 }
//                 return [
//                     "id" => $product->id,
//                     "title" => $product->title,
//                     "slug" => $product->slug,
//                     "price_soles" => $product->price_soles,
//                     "price_usd" => $product->price_usd,
//                     "discount_g" => $discount_g,
//                     "imagen" => env("APP_URL")."storage/".$product->imagen,
//                     "reviews_count" => $product->reviews_count,
//                     "avg_rating" => round($product->avg_rating),
//                 ];
//             }),
//         ]);
//     }

//     $products_aletorio_a = Product::inRandomOrder()->limit(4)->get();

//     $products_aletorio_b = Product::inRandomOrder()->limit(8)->get();

//     return response()->json([
//         "sliders" => $sliders->map(function($slider){
//             return [
//                 "id" => $slider->id,
//                 "url" => $slider->url,
//                 "name" => $slider->name,
//                 "imagen" => env("APP_URL")."storage/".$slider->imagen,
//             ];
//         }),
//         "group_categories_product" => $group_categories_product,
//         "products_aletorio_a" => $products_aletorio_a->map(function($product){
//             return ProductEResource::make($product);
//         }),
//         "products_aletorio_b" => $products_aletorio_b->map(function($product){
//             return ProductEResource::make($product);
//         }),
//     ]);

// }

// public function detail_product($slug_product)
// {
//     $product = Product::where("slug",$slug_product)->first();
//     if(!$product){
//         return response()->json(["message" => 403]);
//     }
//     $product_relateds = Product::where("id","<>",$product->id)->where("categorie_id",$product->categorie_id)->orderBy("id","asc")->get();

//     $reviews = Review::where("product_id",$product->id)->orderBy("id","desc")->paginate(13);

//     $reviews_count = Review::select("rating",DB::raw("count(*) as total"))->where("product_id",$product->id)->groupBy("rating")->orderBy("id","desc")->get();
//     return response()->json(["message" => 200 ,
//         "product_detail" => ProductEResource::make($product),
//         "product_relateds" => $product_relateds->map(function($product){
//             return ProductEResource::make($product);
//         }),
//         "reviews" => $reviews->map(function($review){
//             return [
//                 "id" => $review->id,
//                 "user" => [
//                     "id" => $review->user->id,
//                     "full_name" => $review->user->name . '  ' .  $review->user->surname,
//                     "avatar" => env("APP_URL")."storage/".$review->user->avatar,
//                 ],
//                 "message" => $review->message,
//                 "rating" => $review->rating,
//                 "created_at" => $review->created_at->format("Y/m/d"),
//             ];
//         }),
//         "reviews_count" => $reviews_count,
//     ]);
// }

// public function list_product(Request $request)
// {
//     $categories = $request->categories;
//     $review = $request->review;
//     $min_price = $request->min_price;
//     $max_price = $request->max_price;
//     $size_id = $request->size_id;
//     $color_id = $request->color_id;
//     $search_product = $request->search_product;
//     // ->inRandomOrder() withCount("reviews")->
//     $products = Product::filterAdvance($categories,$review,$min_price,$max_price,$size_id,$color_id,$search_product)->get();

//     return response()->json(["products" => $products->map(function($product){
//         return  ProductEResource::make($product);
//     })]);

// }

// public function config_initial_filter()
// {
//     $categories = Categorie::withCount("products")->orderBy("id","desc")->get();

//     $reviews = Review::select("rating",DB::raw("count(*) as total"))->groupBy("rating")->orderBy("id","desc")->get();

//     $sizes = ProductSize::select("name",DB::raw("count(*) as total"))->groupBy("name")->orderBy("id","desc")->get();

//     $colores = ProductColor::withCount("product_color_sizes")->orderBy("id","desc")->get();
//     return response()->json(["colores" => $colores,"sizes" => $sizes, "categories" => $categories , "reviews" => $reviews]);
// }
// }
