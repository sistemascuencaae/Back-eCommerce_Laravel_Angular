<?php

namespace App\Http\Controllers\Slider;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); //Cualquier user tiene que estar eutenticado
    }

    public function index()
    {
        $sliders = Slider::orderBy("id", "desc")->get();

        return response()->json(["sliders" => $sliders,]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if ($request->hasFile("imagen_file")) {
            $path = Storage::putFile("sliders", $request->file("imagen_file")); //se va a guardar dentro de la CARPETA CATEGORIAS
            $request->request->add(["imagen" => $path]); //Aqui obtenemos la ruta de la imagen en la que se encuentra
        }

        $slider = Slider::create($request->all());

        return response()->json(["slider" => $slider,]);
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
        $slider = Slider::findOrFail($id);

        if ($request->hasFile("imagen_file")) {
            if ($slider->imagen) { //Aqui eliminamos la imagen anterior
                Storage::delete($slider->imagen); //Aqui pasa la rta de la imagen para eliminarlo
            }
            $path = Storage::putFile("sliders", $request->file("imagen_file")); //se va a guardar dentro de la CARPETA CATEGORIAS
            $request->request->add(["imagen" => $path]); //Aqui obtenemos la nueva ruta de la imagen al request
        }

        $slider->update($request->all());

        return response()->json(["slider" => $slider,]);
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->delete();

        return response()->json(["message" => 200]);
    }
}