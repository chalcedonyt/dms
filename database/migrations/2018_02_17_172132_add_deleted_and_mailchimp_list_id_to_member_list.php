<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAndMailchimpListIdToMemberList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_lists', function (Blueprint $table) {
            $table->string("mailchimp_list_id")->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_lists', function (Blueprint $table) {
            $table->dropColumn("mailchimp_list_id");
            $table->dropColumn("deleted_at");
        });
    }
}
