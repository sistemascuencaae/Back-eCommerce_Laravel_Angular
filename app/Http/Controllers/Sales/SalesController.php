<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ecommerce\Sale\SaleOCollection;
use App\Models\Models\Product\Categorie;
use App\Models\Models\Sale\Sale;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function sale_all(Request $request)
    {
        $search = $request->search;
        $categorie_id = $request->categorie_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $orders = Sale::filterAdvance($search, $categorie_id, $start_date, $end_date)->orderBy("id", "desc")->get();

        $categories = Categorie::orderBy("id", "desc")->get();

        return response()->json(["categories" => $categories, "orders" => SaleOCollection::make($orders)]);
    }
}