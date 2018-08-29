<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedCategoriesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categories = [
            [
                'name' => '干货',
                'parent_id' => 0,
            ],
            [
                'name' => '项目',
                'parent_id' => 0,
            ],
            [
                'name' => '资源',
                'parent_id' => 0,
            ],
            [
                'name' => '奇思妙想',
                'parent_id' => 0,
            ],
            [
                'name' => '其他',
                'parent_id' => 0,
            ],
            [
                'name' => '黑科技',
                'parent_id' => 1,
            ],
            [
                'name' => '教程',
                'parent_id' => 1,
            ],
            [
                'name' => '好物推荐',
                'parent_id' => 1,
            ],
            [
                'name' => '讲座活动',
                'parent_id' => 2,
            ],
            [
                'name' => '比赛组队',
                'parent_id' => 2,
            ],
            [
                'name' => '学习资源',
                'parent_id' => 3,
            ],
            [
                'name' => '影音分享',
                'parent_id' => 3,
            ],
            [
                'name' => '奇思妙想',
                'parent_id' => 4,
            ],
            [
                'name' => '日常表白',
                'parent_id' => 4,
            ],
            [
                'name' => '失物招领',
                'parent_id' => 4,
            ],
            [
                'name' => '其他',
                'parent_id' => 5,
            ],
        ];

        DB::table('categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('categories')->truncate();
    }
}
