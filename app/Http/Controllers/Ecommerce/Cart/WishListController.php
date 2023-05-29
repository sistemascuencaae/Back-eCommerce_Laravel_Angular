<?php

namespace App\Http\Controllers\Ecommerce\Cart;

use App\Http\Controllers\Controller;
use App\Models\Models\Sale\Wishlist\Wishlist;
use Illuminate\Http\Request;

class WishListController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where("user_id", auth('api')->user()->id)->orderBy("id", "desc")->get();

        return response()->json(
            [
                "wishlists" => $wishlists->map(function ($wishlist) {
                    return [
                        "id" => $wishlist->id,
                        "user" => [
                            "id" => $wishlist->client->id,
                            "name" => $wishlist->client->name,
                        ],
                        "product_size_id" => $wishlist->product_size_id,
                        "product_color_size_id" => $wishlist->product_color_size_id,
                        "product" => [
                            "id" => $wishlist->product->id,
                            "tittle" => $wishlist->product->tittle,
                            "slug" => $wishlist->product->slug,
                            "price_soles" => $wishlist->product->price_soles,
                            "price_usd" => $wishlist->product->price_usd,
                            // "imagen" => env("APP_URL") . "storage/app/" . $wishlist->product->imagen,
                            "imagen" => env("APP_URL") . "storage/app/" . $wishlist->product->imagen,
                        ],
                    ];
                })
            ]
        );
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validate_wishlist = Wishlist::where("product_id", $request->product_id)->first();
        if ($validate_wishlist) {
            return response()->json(["message" => 403, "message_text" => "EL PRODUCTO SELECCIONADO YA EXISTE"]);
        }

        $wishlist = Wishlist::create($request->all());
        return response([
            "message" => 200,
            "wishlist" => [
                "id" => $wishlist->id,
                "user" => [
                    "id" => $wishlist->client->id,
                    "name" => $wishlist->client->name,
                ],
                "product_size_id" => $wishlist->product_size_id,
                "product_color_size_id" => $wishlist->product_color_size_id,
                "product" => [
                    "id" => $wishlist->product->id,
                    "tittle" => $wishlist->product->tittle,
                    "slug" => $wishlist->product->slug,
                    "price_soles" => $wishlist->product->price_soles,
                    "price_usd" => $wishlist->product->price_usd,
                    // "imagen" => env("APP_URL") . "/storage/app/" . $wishlist->product->imagen,
                    "imagen" => env("APP_URL") . "storage/app/" . $wishlist->product->imagen,
                ],
            ]
        ]);
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();
        return response()->json(["message" => 200]);
    }
}