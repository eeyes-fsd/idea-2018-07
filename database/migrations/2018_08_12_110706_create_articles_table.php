<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('organization_id')->nullable();
            $table->integer('category_id')->comment("分类ID");
            $table->string('title')->comment('标题');
            $table->text('body')->comment('正文');
            $table->boolean('anonymous')->default(false)->comment('是否匿名');
            $table->integer('view_count')->default(0)->comment('浏览数');
            $table->integer('like_count')->default(0)->comment('点赞数');
            $table->integer('favorite_count')->default(0)->comment('收藏数');
            $table->integer('reply_count')->default(0)->comment('回复数');
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
        Schema::dropIfExists('articles');
    }
}
