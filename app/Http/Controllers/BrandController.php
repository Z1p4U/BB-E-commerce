<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brand = Brand::searchQuery()
            ->sortingQuery()
            ->paginationQuery();

        DB::beginTransaction();

        try {
            DB::commit();
            return $this->success("Brand List", $brand);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();
        try {

            $brand = Brand::create($payload->toArray());
            DB::commit();

            return $this->success('Brand created successfully', $brand);
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
            $brand = Brand::findOrFail($id);
            DB::commit();

            return $this->success('Brand Detail', $brand);
        } catch (Exception $e) {
            DB::rollback();

            return $this->notFound("Brand Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, String $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $brand = Brand::findOrFail($id);
            $brand->update($payload->toArray());
            DB::commit();

            return $this->success('Brand updated successfully', $brand);
        } catch (Exception $e) {
            DB::rollback();
            return $this->notFound("Brand Not Found");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        DB::beginTransaction();
        try {

            $brand = Brand::findOrFail($id);

            if (Gate::denies('delete', $brand)) {
                return $this->unauthorized();
            }
            $brand->delete($id);
            DB::commit();

            return $this->success('Brand deleted successfully', $brand);
        } catch (Exception $e) {
            DB::rollback();
            return $this->notFound("Brand Not Found");
        }
    }
}
