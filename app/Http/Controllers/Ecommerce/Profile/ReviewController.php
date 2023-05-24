<?php

namespace App\Http\Controllers\Ecommerce\Profile;

use App\Http\Controllers\Controller;
use App\Models\Models\Sale\Review\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
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
        $request->request->add(["user_id" => auth('api')->user()->id]);
        $review = Review::create($request->all());

        return response()->json(["message" => 200, "review" => $review]);
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
        $review = Review::findOrFail($id);
        $review->update($request->all());
        return response()->json(["message" => 200, "review" => $review]);
    }


    public function destroy($id)
    {
        //
    }
}