<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

/** Version 1 Api 用于前后端交互 */
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1'
], function ($api) {
    //TODO
});

/** Version 2 Api 用于APP交互 */
$api->version('v2', [
    'namespace' => 'App\Http\Controllers\Api\V2'
], function ($api) {
    //TODO
});