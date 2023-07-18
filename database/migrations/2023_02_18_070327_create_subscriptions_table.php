<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->text('note')->nullable();
            $table->string('type')->nullable();
            $table->integer('quantity')->default(0);
            $table->foreignId('user_id');
            $table->foreignId('restaurant_id');
            $table->decimal('billing_amount', 23, 3)->default(0);
            $table->decimal('paid_amount', 23, 3)->default(0);
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
        Schema::dropIfExists('subscriptions');
    }
}
