<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::searchQuery()
            ->sortingQuery()
            ->paginationQuery();

        DB::beginTransaction();
        try {
            $productResource = ProductResource::collection($product);

            DB::commit();

            return $this->success("Product List", $product);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {

        DB::beginTransaction();
        try {

            $product = Product::create([
                "name" => $request->name,
                "short_description" => $request->short_description,
                "description" => $request->description,
                "photo" => $request->photo
            ]);

            $product->categories()->attach($request->input('category_ids'));
            $product->brands()->attach($request->input('brand_ids'));

            DB::commit();

            return $this->success('Product created successfully', $product);
        } catch (Exception $e) {
            DB::rollback();
            // throw $e;
            return $this->notFound('Product creation failed');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($id);

            $productResource = new ProductResource($product);

            DB::commit();

            return $this->success('Product Detail', $productResource);
        } catch (Exception $e) {
            DB::rollback();

            // return $e;
            return $this->notFound('Product Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, String $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);

            $product->update([
                "name" => $request->name,
                "short_description" => $request->short_description,
                "description" => $request->description,
                "photo" => $request->photo
            ]);

            $product->categories()->sync($request->input('category_ids'));
            $product->brands()->sync($request->input('brand_ids'));

            DB::commit();

            return $this->success('Product updated successfully', $product);
        } catch (Exception $e) {
            DB::rollback();
            // throw $e;
            return $this->notFound('Product Update failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        DB::beginTransaction();
        try {

            $product = Product::findOrFail($id);

            if (Gate::denies('delete', $product)) {
                return $this->unauthorized();
            }
            $product->delete($id);
            DB::commit();

            return $this->success('Product deleted successfully', $product);
        } catch (Exception $e) {
            DB::rollback();
            return $this->notFound("Product Not Found");
        }
    }
}
