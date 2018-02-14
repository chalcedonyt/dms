<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTokenAndAvatarUrlToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_url')->nullable();
            $table->mediumText('google_token')->nullable();
            $table->timestamp('google_token_added_at')->nullable();
            $table->timestamp('google_token_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar_url');
            $table->dropColumn('google_token');
            $table->dropColumn('google_token_added_at');
            $table->dropColumn('google_token_expires_at');
        });
    }
}
