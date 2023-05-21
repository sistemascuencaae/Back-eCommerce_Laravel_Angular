<?php

namespace App\Http\Controllers\Cupones;

use App\Http\Controllers\Controller;
use App\Models\Models\Cupon\Cupone;
use App\Models\Models\Product\Categorie;
use App\Models\Models\Product\Product;
use Illuminate\Http\Request;

class CuponesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); //Cualquier user tiene que estar eutenticado
    }
    public function index(Request $request)
    {
        $cupones = Cupone::where("code", "like", "%" . $request->search . "%")
            ->orderBy("id", "desc")->get();

        return response()->json([
            "message" => 200,
            "cupones" => $cupones,
        ]);
    }

    public function config_all()
    {
        $products = Product::where("state", 2)->orderBy("id", "desc")->get();
        $categories = Categorie::orderBy("id", "desc")->get();

        return response()->json([
            "message" => 200,
            "categories" => $categories,
            "products" => $products
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $IS_VALID = Cupone::where("code", $request->code)->first();
        if ($IS_VALID) {
            return response()->json([
                "message" => 403,
                "message_text" => "El codigo de cupon que desea registrar ya existe"
            ]);
        }

        if ($request->type_cupon == 1) {
            $products = [];
            foreach ($request->products_selected as $key => $product) {
                array_push($products, $product["id"]);
            }

            $request->request->add(["products" => implode(",", $products)]);

        } else if ($request->type_cupon == 2) {
            $categories = [];
            foreach ($request->categories_selected as $key => $categorie) {
                array_push($categories, $categorie["id"]);
            }

            $request->request->add(["categories" => implode(",", $categories)]);
        }

        Cupone::create($request->all());

        return response()->json([
            "message" => 200
        ]);
    }

    public function show($id)
    {
        $cupone = Cupone::findOrFail($id);

        return response()->json([
            "message" => 200,
            "cupone" => $cupone,
        ]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $IS_VALID = Cupone::where("id", "<>", $id)
            ->where("code", $request->code)->first();

        if ($IS_VALID) {
            return response()->json([
                "message" => 403,
                "message_text" => "El codigo de cupon que desea registrar ya existe"
            ]);
        }

        if ($request->type_cupon == 1) {
            $products = [];
            foreach ($request->products_selected as $key => $product) {
                array_push($products, $product["id"]);
            }

            $request->request->add(["products" => implode(",", $products)]);

        }

        if ($request->type_cupon == 2) {
            $categories = [];
            foreach ($request->categories_selected as $key => $categorie) {
                array_push($categories, $categorie["id"]);
            }

            $request->request->add(["categories" => implode(",", $categories)]);
        }

        $cupone = Cupone::findOrFail($id);
        $cupone->update($request->all());

        return response()->json([
            "message" => 200
        ]);
    }

    public function destroy($id)
    {
        $cupone = Cupone::findOrFail($id);
        $cupone->delete();

        return response()->json([
            "message" => 200
        ]);
    }
}