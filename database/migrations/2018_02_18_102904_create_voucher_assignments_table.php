<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_list_id');
            $table->integer('member_id');
            $table->integer('voucher_id');
            $table->integer('assigned_by');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index('member_list_id');
            $table->index('member_id');
            $table->index('voucher_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher_assignments');
    }
}
