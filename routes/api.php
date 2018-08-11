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

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function ($api) {

    /** 认证路由 */
    $api->get('users/authorizations', 'AuthorizationsController@userAuthenticate')
        ->name('api.users.authorization.auth');
    $api->post('users/authorizations/callback', 'AuthorizationsController@userCallback')
        ->name('api.users.authorization.callback');
    $api->post('organizations/authorizations', 'AuthorizationsController@organizationAuthenticate')
        ->name('api.organizations.authorizations.auth');
    $api->put('users/refresh','AuthorizationsController@refreshUserToken')
        ->name('api.users.authorizations.refresh');
    $api->put('organizations/refresh','AuthorizationsController@refreshOrganizationToken')
        ->name('api.organizations.authorizations.refresh');
    $api->delete('users/current','AuthorizationsController@userLogout')
        ->name('api.users.authorizations.logout');
    $api->delete('organizations/current','AuthorizationsController@organizationLogout')
        ->name('api.organizations.authorizations.logout');

    $api->group(['middleware' => 'api.a'], function ($api){
        $api->get('user','UsersController@me')
            ->name('api.users.me');
        $api->get('organization','OrganizationsController@me')
            ->name('api.organizations.me');
    });

    $api->resource('organizations','OrganizationsController',['except' => ['create','edit']]);
    $api->resource('users','OrganizationsController',['except' => ['create','store','edit']]);
});
