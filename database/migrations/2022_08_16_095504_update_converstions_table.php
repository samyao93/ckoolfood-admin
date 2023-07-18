<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConverstionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->renameColumn('user_id','sender_id');
            $table->string('sender_type');
            $table->foreignId('receiver_id');
            $table->string('receiver_type');
            $table->foreignId('last_message_id')->nullable();
            $table->timestamp('last_message_time')->nullable();
            $table->integer('unread_message_count')->default(0);
            $table->dropColumn('message');
            $table->dropColumn('reply');
            $table->dropColumn('checked');
            $table->dropColumn('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->renameColumn('sender_id', 'user_id');
            $table->string('message');
            $table->dropColumn('receiver_id');
            $table->dropColumn('receiver_type');
            $table->dropColumn('last_message_id')->nullable();
            $table->dropColumn('last_message_time')->nullable();
            $table->dropColumn('sender_type');
            $table->string('reply');
            $table->boolean('checked');
            $table->string('image')->nullable();
        });
    }
}
