<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Models\Product\ProductImage;
use App\Models\Models\Product\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImagensController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); //Cualquier user tiene que estar eutenticado
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function store(Request $request)
    {
        $file = $request->file("file");
        if ($request->hasFile("file")) {
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();
            $nombre = $file->getClientOriginalName();

            $path = Storage::putFile("productos", $file); //obtenemos la ruta de las imagenes

            $imagen = ProductImage::create([
                "product_id" => $request->product_id,
                "file_name" => $nombre,
                "imagen" => $path,
                "size" => $size,
                "type" => $extension,
            ]);
        }

        return response()->json([
            "imagen" => [
                "id" => $imagen->id,
                "file_name" => $imagen->file_name,
                // "imagen" => env("APP_URL") . "/storage/app/" . $imagen->imagen,
                "imagen" => env("APP_URL") . "storage/app/" . $imagen->imagen,
                "size" => $imagen->size,
                "type" => $imagen->type,
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $imagen = ProductImage::findOrFail($id);

        if ($imagen->imagen) { // Si este registro tiene una imagen, si dice si entra
            Storage::delete($imagen->imagen);
        }

        $imagen->delete();

        return response()->json(["message" => 200]);
    }
}