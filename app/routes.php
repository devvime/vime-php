<?php

$route[] = ['/','RoutesController@index'];
$route[] = ['/api','ApiController@index'];
$route[] = ['/sendmail','EmailController@index'];

return $route;

