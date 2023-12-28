<?php

namespace App\Imports;

use App\Models\Item;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemsImport implements ToModel, ShouldQueue, WithHeadingRow, WithChunkReading
{

    public function model(array $row)
    {
        // Find the existing item by a unique identifier (e.g., 'name')
        $item = Item::where('id', $row['id'])->first();

        // If the item exists, update the 'price' attribute
        if ($item) {
            $item->update([
                'price' => $row['price'],
            ]);
        }

        return null;
    }

    public function chunkSize(): int
    {
        return 100000; // this number represents number of rows
    }
}
