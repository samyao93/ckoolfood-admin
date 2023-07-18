<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsToWithdrawRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            $table->foreignId('delivery_man_id')->nullable();
            $table->foreignId('withdrawal_method_id')->nullable();
            $table->json('withdrawal_method_fields')->nullable();
            $table->foreignId('vendor_id')->nullable()->change();
            $table->foreignId('admin_id')->nullable()->change();
            $table->decimal('amount',23,3)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            Schema::dropIfExists('delivery_man_id');
            Schema::dropIfExists('withdrawal_method_fields');
            Schema::dropIfExists('withdrawal_method_id');
        });
    }
}
