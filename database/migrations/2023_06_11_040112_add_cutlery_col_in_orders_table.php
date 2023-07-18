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
        Schema::table('orders', function (Blueprint $table) {
            $table->double('tax_percentage', 24, 3)->nullable();
            $table->text('delivery_instruction')->nullable();
            $table->string('unavailable_item_note', 255)->nullable();
            $table->boolean('cutlery')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tax_percentage');
            $table->dropColumn('delivery_instruction');
            $table->dropColumn('unavailable_item_note');
            $table->dropColumn('cutlery');
        });
    }
};
