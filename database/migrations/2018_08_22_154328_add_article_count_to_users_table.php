<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArticleCountToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('article_count')->after('notification_count')->unsigned()->default(0)->comment('文章总数');
        });
        Schema::table('organizations', function (Blueprint $table) {
            $table->integer('article_count')->after('notification_count')->unsigned()->default(0)->comment('文章总数');
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
            $table->dropColumn('article_count');
        });
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('article_count');
        });
    }
}
