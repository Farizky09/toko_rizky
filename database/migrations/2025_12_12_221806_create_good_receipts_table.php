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
        Schema::create('good_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('gr_number')->unique();
            $table->unsignedBigInteger('purchase_id');
            $table->date('receipt_date');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('received_by');
            $table->enum('status', ['draft', 'partial', 'completed', 'cancelled'])->default('draft');
            $table->integer('total_items');
            $table->decimal('total_quantity_large', 15, 2);
            $table->decimal('total_quantity_small', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();


            $table->foreign('purchase_id')
                ->references('id')
                ->on('purchases')
                ->onDelete('cascade');
            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('cascade');
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('cascade');
            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('cascade');
            $table->foreign('received_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('good_receipts');
    }
};
