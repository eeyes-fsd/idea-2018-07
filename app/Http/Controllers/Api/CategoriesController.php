<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        //
    }

    public function show(Category $category)
    {
        //
    }

    public function store(Request $request)
    {
        Category::create($request->all());
    }

    public function update(Category $category, Request $request)
    {
        //
    }

    public function destroy(Category $category)
    {
        //
    }
}