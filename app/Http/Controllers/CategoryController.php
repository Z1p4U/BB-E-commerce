<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::searchQuery()
            ->sortingQuery()
            ->paginationQuery();

        DB::beginTransaction();

        try {
            DB::commit();
            return $this->success("Category List", $category);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();
        try {

            $category = Category::create($payload->toArray());
            DB::commit();

            return $this->success('Category created successfully', $category);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        DB::beginTransaction();

        try {
            $category = Category::findOrFail($id);
            DB::commit();

            return $this->success('Category Detail', $category);
        } catch (Exception $e) {
            DB::rollback();

            return $this->notFound("Category Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, String $id)
    {
        $payload = collect($request->validated());


        DB::beginTransaction();
        try {

            $category = Category::findOrFail($id);
            $category->update($payload->toArray());
            DB::commit();

            return $this->success('Category updated successfully', $category);
        } catch (Exception $e) {
            DB::rollback();
            return $this->notFound("Category Not Found");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        DB::beginTransaction();
        try {

            $category = Category::findOrFail($id);

            if (Gate::denies('delete', $category)) {
                return $this->unauthorized();
            }
            $category->delete($id);
            DB::commit();

            return $this->success('Category deleted successfully', $category);
        } catch (Exception $e) {
            DB::rollback();
            return $this->notFound("Category Not Found");
        }
    }
}
