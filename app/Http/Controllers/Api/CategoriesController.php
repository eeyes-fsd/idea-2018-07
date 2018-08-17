<?php

namespace App\Http\Controllers\Api;

use App\Serializers\CustomSerializer;
use App\Transformers\CategoryTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Category;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.a',['except' => ['index','show']]);
    }
    public function index()
    {
        $categories = Category::all();
        return $this->response->collection($categories,new CategoryTransformer());
    }

    public function show(Category $category)
    {
        $manager = new Manager();
        $manager->setSerializer(new CustomSerializer());
        $manager->parseIncludes(['parent','children']);
        return $manager->createData(new Item($category,new CategoryTransformer()))->toArray();

    }

    public function store(Request $request)
    {
//        $this->authorizeForUser($this->getUserOrActiveOrganization(),'create',Category::class);
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
        // todo  what todo with the articles and subcategory?
        // leave this function for the next version

    }
}