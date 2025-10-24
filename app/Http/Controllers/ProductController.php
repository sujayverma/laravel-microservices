<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use App\Http\Requests\ProductCreateRequest;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('view', 'products');
        $products = Product::paginate(10);
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request)
    {
        Gate::authorize('edit', 'products');
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image_url' => $request->image_url
        ]);
        return response()->json(new ProductResource($product), Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        Gate::authorize('view', 'products');
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json(new ProductResource($product), Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCreateRequest $request, $id)
    {
        Gate::authorize('edit', 'products');
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
        $product->update($request->only(['name', 'description', 'price', 'image_url']));
        return response()->json(new ProductResource($product), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Gate::authorize('edit', 'products');
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
        $product->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
