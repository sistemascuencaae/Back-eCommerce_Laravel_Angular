<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Models\Models\Product\Categorie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CategorieController extends Controller
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
        $categories = Categorie::where("name", "like", "%" . $search . "%")
            ->orderBy("id", "desc")->get();

        return response()->json(["categorias" => $categories,]);
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
        if ($request->hasFile("imagen_file")) {
            $path = Storage::putFile("categorias", $request->file("imagen_file")); //se va a guardar dentro de la CARPETA CATEGORIAS
            $request->request->add(["imagen" => $path]); //Aqui obtenemos la ruta de la imagen en la que se encuentra
        }

        $categorie = Categorie::create($request->all());

        return response()->json(["categorie" => $categorie,]);
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
        $categorie = Categorie::findOrFail($id);

        if ($request->hasFile("imagen_file")) {
            if ($categorie->imagen) { //Aqui eliminamos la imagen anterior
                Storage::delete($categorie->imagen); //Aqui pasa la rta de la imagen para eliminarlo
            }
            $path = Storage::putFile("categorias", $request->file("imagen_file")); //se va a guardar dentro de la CARPETA CATEGORIAS
            $request->request->add(["imagen" => $path]); //Aqui obtenemos la nueva ruta de la imagen al request
        }

        $categorie->update($request->all());

        return response()->json(["categorie" => $categorie,]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->delete();

        return response()->json(["message" => 200]);
    }
}