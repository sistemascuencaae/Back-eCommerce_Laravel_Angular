<?php

namespace App\Http\Controllers\Ecommerce\Sale;

use App\Http\Controllers\Controller;
use App\Mail\Sale\SaleMail;
use App\Models\Models\Cart\CartShop;
use App\Models\Models\Sale\Sale;
use App\Models\Models\Sale\SaleAddres;
use App\Models\Models\Sale\SaleAddress;
use App\Models\Models\Sale\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
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

        $sale = Sale::create($request->sale);
        //
        $sale_address = $request->sale_address;
        $sale_address["sale_id"] = $sale->id;
        $sale_address = SaleAddress::create($sale_address);

        //CARRITO DE COMPRA O DETALLE DE VENTA

        $cartshop = CartShop::where("user_id", auth('api')->user()->id)->get();

        foreach ($cartshop as $key => $cart) {
            // $cart->delete();
            $sale_detail = $cart->toArray();
            $sale_detail["sale_id"] = $sale->id;
            SaleDetail::create($sale_detail);
        }
        Mail::to($sale->user->email)->send(new SaleMail($sale));
        return response()->json(["message" => 200, "message_text" => "LA VENTA SE EFECTUO DE MANERA CORRECTA"]);
    }

    public function send_email($id)
    {
        $sale = Sale::findOrFail($id);
        Mail::to("juan.simbana.est@tecazuay.edu.ec")->send(new SaleMail($sale));
        return "TODO SALIO BIEN";
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
        //
    }


    public function destroy($id)
    {
        //
    }
}