<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberListAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_list_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_list_id');
            $table->string('attribute_name');
            $table->timestamps();

            $table->index('member_list_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_list_attributes');
    }
}
