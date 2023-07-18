<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('package_id');
            $table->foreignId('restaurant_id');
            $table->double('price', 24, 3)->default(0);
            $table->integer('validity')->default(0);
            $table->string('payment_method', 191);
            $table->string('reference', 191)->nullable();
            $table->double('paid_amount',24, 2);
            $table->integer('discount')->default(0);
            $table->json('package_details');
            $table->string('created_by', 50);
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
        Schema::dropIfExists('subscription_transactions');
    }
}
