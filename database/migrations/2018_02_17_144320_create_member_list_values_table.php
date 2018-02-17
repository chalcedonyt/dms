<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberListValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_list_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id');
            $table->integer('member_list_id');
            $table->integer('member_list_attribute_id');
            $table->string('value')->nullable();
            $table->timestamps();

            $table->index('member_id');
            $table->index('member_list_id');
            $table->index('member_list_attribute_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_list_values');
    }
}
