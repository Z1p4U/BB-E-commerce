<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $item = Item::searchQuery()
            ->sortingQuery()
            ->paginationQuery();

        DB::beginTransaction();
        try {

            $itemResource = ItemResource::collection($item);
            DB::commit();

            return $this->success("Item List", $item);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();
        try {
            if ($payload['sale'] === null) {
                $payload['sale'] = 0;
            };

            $item = Item::create($payload->toArray());

            DB::commit();

            return $this->success('Item created successfully', $item);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
            // return $this->notFound('Item creation failed');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        DB::beginTransaction();

        try {
            $item = Item::findOrFail($id);

            $itemResource = new ItemResource($item);

            DB::commit();

            return $this->success('Item Detail', $itemResource);
        } catch (Exception $e) {
            DB::rollback();

            // return $e;
            return $this->notFound('Item Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, String $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $item = Item::findOrFail($id);
            $item->update($payload->toArray());
            DB::commit();

            return $this->success('Item updated successfully', $item);
        } catch (Exception $e) {
            DB::rollback();
            return $this->notFound("Item Not Found");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        DB::beginTransaction();
        try {

            $item = Item::findOrFail($id);

            if (Gate::denies('delete', $item)) {
                return $this->unauthorized();
            }
            $item->delete($id);
            DB::commit();

            return $this->success('Item deleted successfully', $item);
        } catch (Exception $e) {
            DB::rollback();
            return $this->notFound("Item Not Found");
        }
    }
}
