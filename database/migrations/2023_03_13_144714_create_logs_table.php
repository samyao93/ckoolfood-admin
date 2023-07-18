<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logable_id');
            $table->string('logable_type');
            $table->string('action_type',50);
            $table->string('model');
            $table->foreignId('model_id');
            $table->string('action_details')->nullable();
            $table->string('ip_address')->nullable();
            $table->json('before_state')->nullable();
            $table->json('after_state')->nullable();
            $table->foreignId('restaurant_id')->nullable();
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
        Schema::dropIfExists('logs');
    }
}
