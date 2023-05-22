<?php

namespace App\Http\Controllers\Ecommerce\Client;

use App\Http\Controllers\Controller;
use App\Models\Models\Client\AddressUser;
use Illuminate\Http\Request;

class AddressUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $address = AddressUser::where("user_id", auth('api')->user()->id)->orderBy("id", "desc")->get();
        return response()->json(["address" => $address]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->request->add(["user_id" => auth("api")->user()->id]);
        $address = AddressUser::create($request->all());
        return response()->json(["message" => 200, "address" => $address]);
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
        $address = AddressUser::findOrFail($id);
        $address->update($request->all());
        return response()->json(["message" => 200, "address" => $address]);
    }


    public function destroy($id)
    {
        $address = AddressUser::findOrFail($id);
        $address->delete();
        return response()->json(["message" => 200]);
    }
}