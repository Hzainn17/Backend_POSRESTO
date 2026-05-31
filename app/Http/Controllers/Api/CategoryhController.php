<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryhController extends Controller
{
    //index api
    public function index()
    {
        $categories = \App\Models\Category::paginate(10);
        return response()->json(['status' => 'success', 'message' => 'Categories retrieved successfully', 'data' => $categories]);
    }
}
