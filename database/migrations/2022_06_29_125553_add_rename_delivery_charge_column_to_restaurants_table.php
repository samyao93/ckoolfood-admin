<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRenameDeliveryChargeColumnToRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->renameColumn('delivery_charge', 'minimum_shipping_charge');
            $table->double('per_km_shipping_charge',16, 3, true)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->renameColumn('minimum_shipping_charge', 'delivery_charge');
            $table->dropColumn('per_km_shipping_charge');
        });
    }
}
