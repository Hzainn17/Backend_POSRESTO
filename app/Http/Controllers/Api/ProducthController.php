<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProducthController extends Controller
{
    //index api
    public function index()
    {
        $products = \App\Models\Product::paginate(10);
        return response()->json(['status' => 'success', 'message' => 'Products retrieved successfully', 'data' => $products]);
    }
}
