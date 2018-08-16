<?php

namespace App\Http\Controllers\Api;

use App\Transformers\CategoryTransformer;
use Dingo\Api\Transformer\Adapter\Fractal;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.a',['except' => ['index','show']]);
    }
    public function index()
    {
        //
    }

    public function show(Category $category)
    {
        Fractal::includes(['children','parents'])->item($category, new CategoryTransformer());
    }

    public function store(Request $request)
    {
        $this->authorizeForUser($this->getUserOrActiveOrganization(),'create',Category::class);
        Category::findOrFail($request->parent_id);
        $category = Category::create($request->only(['parent_id','name']));
        $transformer = new CategoryTransformer();
        return $this->success(201,'创建类别成功',$transformer->transform($category));
    }

    public function update(Category $category, Request $request)
    {
        $this->authorizeForUser($this->getUserOrActiveOrganization(),'update',$category);
        Category::findOrFail($request->parent_id);
        $category = Category::create($request->only(['parent_id','name']));
        $transformer = new CategoryTransformer();
        return $this->success(201,'修改类别成功',$transformer->transform($category));
    }

    public function destroy(Category $category)
    {
        //todo
//        $this->authorizeForUser($this->getUserOrActiveOrganization(),'delete',$category);

    }
}