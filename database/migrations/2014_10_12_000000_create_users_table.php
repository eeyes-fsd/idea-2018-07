<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('姓名');
            $table->string('username')->comment('NetID');
            $table->string('email')->unique()->comment('邮箱');
            $table->string('password')->comment('bcrypt密码');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('profile_photo')->nullable()->comment('头像');
            $table->string('signature')->default('这个人很懒，什么也没有写')->comment('个性签名');
            $table->string('phone')->comment('手机');
            $table->string('qq')->nullable()->comment('QQ');
            $table->boolean('phone_visibility')->default(false)->comment('手机是否公开');
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
        Schema::dropIfExists('users');
    }
}
