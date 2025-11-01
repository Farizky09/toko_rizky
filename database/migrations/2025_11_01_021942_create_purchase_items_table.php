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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('purchase_price_large', 15, 2)->nullable();
            $table->decimal('purchase_price_small', 15, 2)->nullable();
            $table->decimal('selling_price_large', 15, 2)->nullable();
            $table->decimal('selling_price_small', 15, 2)->nullable();
            $table->decimal('qty_large', 15, 2)->default(0);
            $table->decimal('qty_small', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);

            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
