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
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->decimal('qty_received_large', 15, 2)
                ->nullable()
                ->default(0)
                ->after('qty_small');
            $table->decimal('qty_received_small', 15, 2)
                ->nullable()
                ->default(0)
                ->after('qty_received_large');
            $table->date('expiry_date')
                ->nullable()
                ->after('subtotal');

            $table->string('item_notes')
                ->nullable()
                ->after('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn(['qty_received_large', 'qty_received_small', 'expiry_date', 'item_notes']);
        });
    }
};
