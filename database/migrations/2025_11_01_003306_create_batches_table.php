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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('location_id');
            $table->string('batch_number');
            $table->decimal('purchase_price_large', 15, 2);
            $table->decimal('purchase_price_small', 15, 2);
            $table->decimal('quantity_large', 15, 2);
            $table->decimal('quantity_small', 15, 2);
            $table->decimal('selling_price_large', 15, 2);
            $table->decimal('selling_price_small', 15, 2);
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired', 'sold_out'])->default('active');


            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
