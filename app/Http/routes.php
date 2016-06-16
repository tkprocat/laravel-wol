<?php
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    // Set our namespace for the underlying routes
   $api->group(['namespace' => 'LaravelWOL\API\Controllers', 'middleware' => 'cors'], function ($api) {
        // Login route
        $api->post('login', 'AuthController@authenticate');
        $api->post('register', 'AuthController@register');

        $api->group( [ 'middleware' => 'jwt.auth' ], function ($api) {
            $api->get('users/me', 'AuthController@me');
            $api->get('validate_token', 'AuthController@validateToken');
            $api->get('users', 'UserController@index');
            $api->post('users', 'UserController@store');
            $api->get('users/{id}', 'UserController@show');
            $api->delete('users/{id}', 'UserController@destroy');
            $api->put('users/{id}', 'UserController@update');
            $api->put('users/{id}/changePassword', 'UserController@changePassword');


            $api->get('devices', 'DeviceController@index');
            $api->post('devices', 'DeviceController@store');
            $api->get('devices/{id}', 'DeviceController@show');
            $api->delete('devices/{id}', 'DeviceController@destroy');
            $api->put('devices/{id}', 'DeviceController@update');
            $api->post('devices/boot', 'DeviceController@boot');
            $api->post('devices/ping', 'DeviceController@ping');
        });
    });
});