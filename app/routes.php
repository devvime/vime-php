<?php

use Core\Middleware\Request;

$request = new Request;

$request->method('GET') && $route[] = ['/','RoutesController@index'];
$request->method('GET') && $route[] = ['/api','ApiController@index'];
$request->method('GET') && $route[] = ['/user','UserController@index'];
$request->method('GET') && $route[] = ['/user/{id}','UserController@read'];
$request->method('GET') && $route[] = ['/auth','AuthController@index'];
$request->method('POST') && $route[] = ['/auth','AuthController@auth'];
$request->method('POST') && $route[] = ['/sendmail','EmailController@index'];
$request->method('POST') && $route[] = ['/api','ApiController@store'];
$request->method('PUT') && $route[] = ['/api','ApiController@update'];
$request->method('DELETE') && $route[] = ['/api','ApiController@destroy'];

return $route;

