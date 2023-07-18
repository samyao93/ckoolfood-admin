<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id');
            $table->foreignId('restaurant_id');
            $table->date('expiry_date');
            $table->string('max_order');
            $table->string('max_product');
            $table->boolean('pos')->default(false);
            $table->boolean('mobile_app')->default(false);
            $table->boolean('chat')->default(false);
            $table->boolean('review')->default(false);
            $table->boolean('self_delivery')->default(false);
            $table->boolean('status')->default(true);
            $table->tinyInteger('total_package_renewed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_subscriptions');
    }
}
