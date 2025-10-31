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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('unit_large_id');
            $table->unsignedBigInteger('unit_small_id');
            $table->integer('conversion');
            $table->integer('min_stock');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
            $table->foreign('unit_large_id')
                ->references('id')
                ->on('unit_larges')
                ->onDelete('cascade');
            $table->foreign('unit_small_id')
                ->references('id')
                ->on('unit_smalls')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
