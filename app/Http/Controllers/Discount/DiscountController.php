<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\Controller;
use App\Http\Resources\Discount\DiscountCollection;
use App\Http\Resources\Discount\DiscountResource;
use App\Models\Models\Discount\Discount;
use App\Models\Models\Discount\DiscountCategorie;
use App\Models\Models\Discount\DiscountProduct;
use Illuminate\Http\Request;

class DiscountController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $discounts = Discount::where("code", "like", "%" . $request->search . "%")->orderBy("id", "desc")->get();

        return response()->json(["discounts" => DiscountCollection::make($discounts)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_array = [];
        $categorie_array = [];
        if ($request->type == 1) {
            foreach ($request->products_selected as $key => $product) {
                array_push($product_array, $product["id"]);
            }
        }
        if ($request->type == 2) {
            foreach ($request->categories_selected as $key => $categorie) {
                array_push($categorie_array, $categorie["id"]);
            }
        }
        $IS_EXISTS_START_DATE = Discount::ValidateDiscount($request, $product_array, $categorie_array)->whereBetween("start_date", [$request->start_date, $request->end_date])->first();
        $IS_EXISTS_END_DATE = Discount::ValidateDiscount($request, $product_array, $categorie_array)->whereBetween("end_date", [$request->start_date, $request->end_date])->first();

        if ($IS_EXISTS_START_DATE || $IS_EXISTS_END_DATE) {
            return response()->json(["message" => 403, "message_text" => "NO PUEDES REGISTRAR ESTE DESCUENTO "]);
        }
        $request->request->add(["code" => uniqid()]);
        $DISCOUNT = Discount::create($request->all());
        if ($request->type == 1) {
            foreach ($product_array as $key => $product) {
                DiscountProduct::create([
                    "discount_id" => $DISCOUNT->id,
                    "product_id" => $product,
                ]);
            }
        }
        if ($request->type == 2) {
            foreach ($categorie_array as $key => $categorie) {
                DiscountCategorie::create([
                    "discount_id" => $DISCOUNT->id,
                    "categorie_id" => $categorie,
                ]);
            }
        }
        return response()->json(["message" => 200, "discount" => $DISCOUNT]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discount = Discount::findOrFail($id);
        return response()->json(["discount" => DiscountResource::make($discount)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product_array = [];
        $categorie_array = [];
        if ($request->type == 1) {
            foreach ($request->products_selected as $key => $product) {
                array_push($product_array, $product["id"]);
            }
        }
        if ($request->type == 2) {
            foreach ($request->categories_selected as $key => $categorie) {
                array_push($categorie_array, $categorie["id"]);
            }
        }
        $IS_EXISTS_START_DATE = Discount::where("id", "<>", $id)->ValidateDiscount($request, $product_array, $categorie_array)->whereBetween("start_date", [$request->start_date, $request->end_date])->first();
        $IS_EXISTS_END_DATE = Discount::where("id", "<>", $id)->ValidateDiscount($request, $product_array, $categorie_array)->whereBetween("end_date", [$request->start_date, $request->end_date])->first();

        if ($IS_EXISTS_START_DATE || $IS_EXISTS_END_DATE) {
            return response()->json(["message" => 403, "message_text" => "NO PUEDES REGISTRAR ESTE DESCUENTO "]);
        }

        $DISCOUNT = Discount::findOrFail($id);
        $DISCOUNT->update($request->all());
        if ($request->type == 1) {
            $DISCOUNT->products()->delete();
            foreach ($product_array as $key => $product) {
                DiscountProduct::create([
                    "discount_id" => $DISCOUNT->id,
                    "product_id" => $product,
                ]);
            }
        }
        if ($request->type == 2) {
            $DISCOUNT->categories()->delete();
            foreach ($categorie_array as $key => $categorie) {
                DiscountCategorie::create([
                    "discount_id" => $DISCOUNT->id,
                    "categorie_id" => $categorie,
                ]);
            }
        }
        return response()->json(["message" => 200, "discount" => $DISCOUNT]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $DISCOUNT = Discount::findOrFail($id);
        $DISCOUNT->delete();

        return response()->json(["message" => 200]);
    }
}