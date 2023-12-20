<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->foreignId("product_id")->constrained()->cascadeOnDelete();
            $table->string("size");
            $table->boolean('sale')->default(false);
            $table->string("price");
            $table->string("discount_price")->nullable();
            $table->string("description")->nullable();
            $table->string("photo")->nullable();
            $table->auditColumns();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
