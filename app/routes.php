<?php

$route[] = ['/','RoutesController@index'];
$route[] = ['/api','ApiController@index'];
$route[] = ['/user','UserController@index'];
$route[] = ['/auth','AuthController@index'];
$route[] = ['/sendmail','EmailController@index'];

return $route;

