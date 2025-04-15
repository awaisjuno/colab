<?php

use System\Routing;

Routing::get('/', 'Pages@index');
Routing::get('/home/{id}', 'Pages@home');
Routing::post('/login', 'AuthController@login');
Routing::put('/user/{id}', 'UserController@update', ['AuthMiddleware']);
Routing::delete('/user/{id}', 'UserController@delete', ['AuthMiddleware']);
