<?php

use Core\Route;

// rotas de email
Route::post() && $route[] = ['/api/sendmail','EmailController@index'];

// rotas de usuarios
Route::get() && $route[] = ['/api/user','UserController@index'];
Route::get() && $route[] = ['/api/user/{id}','UserController@read'];
Route::post() && $route[] = ['/api/user','UserController@store'];
Route::put() && $route[] = ['/api/user','UserController@update'];
Route::delete() && $route[] = ['/api/user/{id}','UserController@destroy'];

// rotas de autenticação
Route::post() && $route[] = ['/api/auth','AuthController@auth'];

