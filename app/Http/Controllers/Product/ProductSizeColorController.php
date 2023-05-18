<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Models\Product\ProductColorSize;
use App\Models\Models\Product\ProductSize;
use Illuminate\Http\Request;

class ProductSizeColorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); //Cualquier user tiene que estar eutenticado
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if (!$request->product_size_id) { //Cuando no ingresa una dimension ya existente

            $product_size_color = ProductSize::where("name", $request->new_nombre, )->first();
            if ($product_size_color) {
                return response()->json([
                    "message" => 403,
                    "text_message" => "Este nombre de dimensión ya existe.",
                ]);
            }

            $product_size = ProductSize::create([
                "product_id" => $request->product_id,
                "name" => $request->new_nombre, //Dimension o tamaño
            ]);
        } else { //En caso de que yo seleccione una dimension del select
            $product_size = ProductSize::findOrFail($request->product_size_id);
        }

        $product_size_color = ProductColorSize::where("product_color_id", $request->product_color_id, )
            ->where("product_size_id", $product_size->id)->first();
        if ($product_size_color) {
            return response()->json([
                "message" => 403,
                "text_message" => "Está configuración ya existe.",
            ]);
        }

        $product_size_color = ProductColorSize::create([
            "product_color_id" => $request->product_color_id,
            "product_size_id" => $product_size->id,
            //En el caso de que yo seleccione una dimension ya me coloca el id
            "stock" => $request->stock,
        ]);

        return response()->json([
            "message" => 200,
            "product_size_color" => $product_size_color
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

    public function update_size(Request $request, $id)
    {
        $product_size_color = ProductSize::where("id", "<>", $id)
            ->where("name", $request->name, )->first();
        if ($product_size_color) {
            return response()->json([
                "message" => 403,
                "text_message" => "Este nombre de dimensión ya existe.",
            ]);
        }

        $product_size = ProductSize::findOrFail($id);
        $product_size->update($request->all());

        return response()->json([
            "message" => 200,
            "product_size" => [
                "id" => $product_size->id,
                "name" => $product_size->name,
                "variaciones" => $product_size->product_size_colors
                    ->map(function ($var) {
                        return [
                            "id" => $var->id,
                            "product_color_id" => $var->product_color_id,
                            "product_color" => $var->product_color,
                            "stock" => $var->stock,
                        ];
                    }),
                "total" => $product_size->product_size_colors->sum("stock"),
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $product_size_color = ProductColorSize::where("id", "<>", $id)
            ->where("product_color_id", $request->product_color_id, )
            ->where("product_size_id", $request->product_size_id)->first();
        if ($product_size_color) {
            return response()->json([
                "message" => 403,
                "text_message" => "Está configuración ya existe.",
            ]);
        }

        $product_color_size = ProductColorSize::findOrFail($id);

        $product_color_size->update($request->all());

        return response()->json([
            "message" => 200,
            "product_color_size" => [
                "id" => $product_color_size->id,
                "product_color_id" => $product_color_size->product_color_id,
                "product_color" => $product_color_size->product_color,
                "stock" => $product_color_size->stock,
            ],
        ]);
    }

    public function destroy_size($id)
    {
        $product_size = ProductSize::findOrFail($id);
        $product_size->delete();

        return response()->json(["message" => 200]);
    }

    public function destroy($id)
    {
        $product_size = ProductColorSize::findOrFail($id);
        $product_size->delete();

        return response()->json(["message" => 200]);
    }
}