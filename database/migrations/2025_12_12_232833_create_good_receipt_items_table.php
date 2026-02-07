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
        Schema::create('good_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_receipt_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('purchase_items_id');
            $table->decimal('qty_received_large', 15, 2);
            $table->decimal('qty_received_small', 15, 2);
            $table->decimal('qty_rejected_large', 15, 2);
            $table->decimal('qty_rejected_small', 15, 2);
            $table->text('reject_reason')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('note')->nullable();

            $table->foreign('good_receipt_id')
                ->references('id')
                ->on('good_receipts')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
            $table->foreign('purchase_items_id')
                ->references('id')
                ->on('purchase_items')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('good_receipt_items');
    }
};
