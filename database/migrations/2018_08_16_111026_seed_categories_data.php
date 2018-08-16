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
