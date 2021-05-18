<?php

namespace Core;

class Container
{
	public static function newController($controller)
	{
		$objController = "App\\Controllers\\". $controller;
		return new $objController;
	}

	public static function getModel($model)
	{
		$objModel = "\\App\\Models\\" . $model;
		return new $objModel();
	}

	public static function pageNotFound()
	{
		if(file_exists(__DIR__ . "/../app/views/website/404.phtml")){
			return require_once __DIR__ . "/../app/views/website/404.phtml";
		}else{
			echo "Error 404: Page not Found!";
		}
	}
}
