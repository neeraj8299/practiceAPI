<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->app->version();
});

$app->group([
    'prefix' => 'v1'
], function () use ($app) {
    $app->post('login','v1\AuthenticateController@login');
    $app->post('register','v1\AuthenticateController@register');
    $app->post('send-otp','v1\AuthenticateController@sendOtp');
    $app->post('update-password','v1\AuthenticateController@updatePassword');
});