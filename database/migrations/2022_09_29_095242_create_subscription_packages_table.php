<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->id();
            $table->string('package_name',191);
            $table->double('price', 24, 3);
            $table->integer('validity');
            $table->string('max_order')->default('unlimited');
            $table->string('max_product')->default('unlimited');
            $table->boolean('pos')->default(false);
            $table->boolean('mobile_app')->default(false);
            $table->boolean('chat')->default(false);
            $table->boolean('review')->default(false);
            $table->boolean('self_delivery')->default(false);
            $table->boolean('status')->default(true);
            $table->boolean('default')->default(false);
            $table->string('colour',50)->nullable();
            $table->text('text')->nullable();
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
        Schema::dropIfExists('subscription_packages');
    }
}
