<?php

namespace App\Http\Controllers\Ecommerce\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ecommerce\Sale\SaleOCollection;
use App\Models\Models\Client\AddressUser;
use App\Models\Models\Sale\Sale;
use App\Models\Models\Sale\SaleDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();

        $address = AddressUser::where("user_id", $user->id)->orderBy("id", "desc")->get();

        $orders = Sale::where("user_id", $user->id)->orderBy("id", "desc")->get();

        $sales_details = SaleDetail::whereHas("sale", function ($q) use ($user) {
            $q->where("user_id", $user->id);
        })->with(["review", "product", "sale"])->orderBy("sale_id", "desc")->get();

        // $wishlists = Wishlist::where("user_id",auth('api')->user()->id)->orderBy("id","desc")->get();
        return response()->json([
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
                "surname" => $user->surname,
                "email" => $user->email,
                "birthday" => $user->birthday ? Carbon::parse($user->birthday)->format("Y-m-d") : NULL,
                "gender" => $user->gender,
                // IMAGEN DEL USUARIO EN PROFILE-INFORMATION
                "avatar" => $user->avatar ? env("APP_URL") . "storage/app/public/" . $user->avatar : null,
                "phone" => $user->phone,
            ],
            "address" => $address->map(function ($addres) {
                return [
                    "id" => $addres->id,
                    "full_name" => $addres->full_name,
                    "full_surname" => $addres->full_surname,
                    "company_name" => $addres->company_name,
                    "county_region" => $addres->county_region,
                    "direccion" => $addres->direccion,
                    "city" => $addres->city,
                    "zip_code" => $addres->zip_code,
                    "phone" => $addres->phone,
                    "email" => $addres->email,
                ];
            }),
            "orders" => SaleOCollection::make($orders),
            "reviews" => $sales_details->map(function ($sale_detail) {
                return [
                    "id" => $sale_detail->id,
                    "n_transaccion" => $sale_detail->sale->n_transaccion,
                    "created_at" => $sale_detail->created_at->format("Y-m-d"),
                    "product" => [
                        "id" => $sale_detail->product_id,
                        "tittle" => $sale_detail->product->tittle,
                        "imagen" => env("APP_URL") . "storage/app/" . $sale_detail->product->imagen,
                    ],
                    "total" => $sale_detail->total,
                    "currency_payment" => $sale_detail->sale->currency_payment,
                    "review" => $sale_detail->review,
                ];
            }),
            // "wishlists" => $wishlists->map(function($wishlist){
            //     return [
            //         "id" => $wishlist->id,
            //         "user" => [
            //             "id" => $wishlist->client->id,
            //             "name" =>$wishlist->client->name,
            //         ],
            //         "product" => [
            //             "id" =>  $wishlist->product->id,
            //             "title" => $wishlist->product->title,
            //             "slug" => $wishlist->product->slug,
            //             "price_soles" => $wishlist->product->price_soles,
            //             "price_usd" => $wishlist->product->price_usd,
            //             "imagen" =>  env("APP_URL")."storage/".$wishlist->product->imagen,
            //         ],
            //     ];
            // }),

        ]);
    }

    public function profile_update(Request $request)
    {
        $user = auth('api')->user();

        if ($request->current_password) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(["message" => 403, "message_text" => "LA CONTRASEÑA ACTUAL INGRESADA NO ES LA CORRECTA"]);
            }
        }

        if ($request->hasFile("imagen")) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $path = Storage::putFile("users", $request->file("imagen"));
            $request->request->add(["avatar" => $path]);
        }

        $users_m = User::find($user->id);
        $users_m->update($request->all());

        return response()->json([
            "message" => 200,
            "user" =>
            [
                "id" => $users_m->id,
                "name" => $users_m->name,
                "surname" => $users_m->surname,
                "email" => $users_m->email,
                "birthday" => $users_m->birthday ? Carbon::parse($users_m->birthday)->format("Y-m-d") : NULL,
                "gender" => $users_m->gender,
                "avatar" => $users_m->avatar ? env("APP_URL") . "storage/" . $users_m->avatar : null,
                "phone" => $users_m->phone,
            ]
        ]);
    }
}