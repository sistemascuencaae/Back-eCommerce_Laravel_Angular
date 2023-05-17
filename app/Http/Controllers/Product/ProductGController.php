<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductCCollection;
use App\Http\Resources\Product\ProductCResouce;
use App\Models\Models\Product\Categorie;
use App\Models\Models\Product\Product;
use App\Models\Models\Product\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductGController extends Controller
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
    public function index(Request $request)
    {
        $search = $request->search;
        $categorie_id = $request->categorie_id;

        $products = Product::filterProduct($search, $categorie_id)
            ->orderBy("id", "desc")->paginate(30);

        return response()->json([
            "message" => 200,
            "total" => $products->total(),
            "products" => ProductCCollection::make($products), //Para enviar una collection al front
        ]);
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

    public function get_info_categories()
    {
        $categories = Categorie::orderBy("id", "desc")->get();
        return response()->json(["categories" => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $is_exists_product = Product::where("tittle", $request->tittle)->first(); //Si el producto ya existe
        if ($is_exists_product) {
            return response()->json(["message" => 403]);
        }

        // $request->request->add([
        //     "tags" => implode(",", $request->tags_e) //enviamos los datos con el nombre de la variable tags_e
        // ]); //implode nos deja convertir un array en string

        $request->request->add(["slug" => Str::slug($request->tittle)]); // Slug son los espaciados del titulo ejmplo: tv-smart

        if ($request->hasFile("imagen_file")) {
            $path = Storage::putFile("productos", $request->file("imagen_file")); //se va a guardar dentro de la CARPETA CATEGORIAS
            $request->request->add(["imagen" => $path]); //Aqui obtenemos la ruta de la imagen en la que se encuentra
        }

        $product = Product::create($request->all());

        //El array de imagenes del front
        foreach ($request->file("files") as $key => $file) {
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();
            $nombre = $file->getClientOriginalName();

            $path = Storage::putFile("productos", $file); //obtenemos la ruta de las imagenes

            ProductImage::create([
                "product_id" => $product->id,
                "file_name" => $nombre,
                "imagen" => $path,
                "size" => $size,
                "type" => $extension,
            ]);
        }

        return response()->json(["message" => 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            "product" => ProductCResouce::make($product),
        ]);
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
        $is_exists_product = Product::where("id", "<>", $id)
            ->where("tittle", $request->tittle)->first(); //Si el producto ya existe
        if ($is_exists_product) {
            return response()->json(["message" => 403]);
        }

        $product = Product::findOrFail($id);

        $request->request->add(["slug" => Str::slug($request->tittle)]); // Slug son los espaciados del titulo ejmplo: tv-smart

        if ($request->hasFile("imagen_file")) {
            $path = Storage::putFile("productos", $request->file("imagen_file")); //se va a guardar dentro de la CARPETA CATEGORIAS
            $request->request->add(["imagen" => $path]); //Aqui obtenemos la ruta de la imagen en la que se encuentra
        }

        $product->update($request->all());

        return response()->json(["message" => 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}