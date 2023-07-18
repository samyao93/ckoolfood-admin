<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable();
            $table->string('sender_type')->nullable();
            $table->foreignId('receiver_id')->default(0);
            $table->string('receiver_type')->nullable();
            $table->integer('unread_message_count')->nullable();
            $table->foreignId('last_message_id')->nullable();
            $table->dateTime('last_message_time')->nullable();
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
        Schema::dropIfExists('conversations');
    }
}
