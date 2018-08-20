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
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['bindings','serializer:array']
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
        $api->post('organizations/activate/{organization}','OrganizationsController@activate');

        $api->post('users/founder/{user}','RolesController@assignUserFounder');
        $api->post('users/maintainer/{user}','RolesController@assignUserMaintainer');

        $api->post('favorites','FavoriteController@storeOrDestroy');
        $api->get('favorites','FavoriteController@index');

        $api->post('likes','LikeController@storeOrDestroy');
        $api->get('likes','LikeController@index');

    });

    $api->get('search/users','SearchController@searchUser');
    $api->get('search/organization','SearchController@searchOrganization');
    $api->get('search/article','SearchController@searchArticle');

    $api->resource('organizations','OrganizationsController',['except' => ['create','edit']]);
    $api->resource('users','UsersController',['except' => ['create','store','edit']]);
    $api->resource('articles','ArticlesController',['except' => ['create','edit']]);
    $api->resource('categories','CategoriesController',['except' => ['create','edit']]);
    $api->resource('replies','RepliesController',['except' => ['create','edit']]);
    $api->resource('notifications','NotificationsController',['except' => ['create','edit','update']]);
    $api->post('notifications/read','NotificationsController@read');
});
