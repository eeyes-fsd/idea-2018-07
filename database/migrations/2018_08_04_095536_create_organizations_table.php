<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique()->comment('社团名称');
            $table->string('email')->unique()->comment('邮箱');
            $table->string('password')->comment('密码');
            $table->string('profile_photo')->nullable()->comment('头像');
            $table->boolean('active')->default(false);
            $table->string('qq')->nullable()->comment('QQ');
            $table->boolean('email_visibility')->default(true)->comment('邮箱是否公开');
            $table->boolean('qq_visibility')->default(true)->comment('QQ是否公开');
            $table->rememberToken();
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
        Schema::dropIfExists('organizations');
    }
}
