<?php

use Core\Middleware\Request;

$request = new Request;
// =====================GET===========================================
$request->method('GET') && $route[] = ['/','RoutesController@index'];
$request->method('GET') && $route[] = ['/api','ApiController@index'];
$request->method('GET') && $route[] = ['/user','UserController@index'];
$request->method('GET') && $route[] = ['/user/{id}','UserController@read'];
$request->method('GET') && $route[] = ['/auth','AuthController@index'];
$request->method('GET') && $route[] = ['/user','UserController@index'];
// =====================POST===========================================
$request->method('POST') && $route[] = ['/user','UserController@store'];
$request->method('POST') && $route[] = ['/auth','AuthController@auth'];
$request->method('POST') && $route[] = ['/sendmail','EmailController@index'];
$request->method('POST') && $route[] = ['/api','ApiController@store'];
// =====================put===========================================
$request->method('PUT') && $route[] = ['/api','ApiController@update'];
$request->method('PUT') && $route[] = ['/user','UserController@update'];
// =====================delete===========================================
$request->method('DELETE') && $route[] = ['/api','ApiController@destroy'];
$request->method('DELETE') && $route[] = ['/user','UserController@destroy'];

return $route;

