<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api'); //Cualquier user tiene que estar eutenticado
    }

    public function index(Request $request)
    {
        $state = $request->get("state");
        $search = $request->get("search");

        $users = User::filterAdvance($state, $search)->where("type_user", 2)
            ->where("type_user", 2)->orderBy("id", "desc") //Ordenar para que los usuarios recien creados salgan primero
            ->paginate(20); //Si pongo aqui 20 tambien tengo  que poner 20 en el html pagezise

        return response()->json([
            "total" => $users->total(),
            //Recibe el total de las paginas
            "users" => $users,
        ]);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $user = User::where("email", $request->email)->first(); //vER SI ESTA DUPLICADO EL EMAIL (TIENE QUE SER UNICO)

        if ($user) { //Si existe
            return response()->json(["message" => 400]);
        } else {
            $user = User::create($request->all());
            return response()->json(["message" => 200, "user" => $user]);
        }
    }

    public function show($id)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
        $user = User::where("email", $request->email)-> //vER SI ESTA DUPLICADO EL EMAIL (TIENE QUE SER UNICO)
            where("id", "<>", $id)->first(); //vER SI ES DIFERENTE EL ID DEL USUARIO

        if ($user) { //Si existe
            return response()->json(["message" => 400]);
        } else {
            $user = User::findOrFail($id); // Busca al usuario por el id
            $user->update($request->all());
            return response()->json(["message" => 200, "user" => $user]);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(["message" => 200]);
    }
}