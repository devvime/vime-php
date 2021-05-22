<?php

namespace Core;

abstract class BaseController
{
	protected $view;
	private $viewPath;
	private $layoutPath;
	private $pageTitle = null;

	public function __construct()
	{
		$this->view = new \stdClass;		
	}

	protected function renderView($viewPath, $layoutPath = null)
	{
		$this->viewPath = $viewPath;
		$this->layoutPath = $layoutPath;
		if ($layoutPath) {
			return $this->layout();
		}else{
			return $this->content();
		}
	}

	protected function content()
	{
		if (file_exists(__DIR__ . "/../app/Views/{$this->viewPath}.phtml")) {
			return require_once __DIR__ . "/../app/Views/{$this->viewPath}.phtml";
		}else{
			echo "Error: View Path not found!";
		}
	}

	protected function layout()
	{
		if (file_exists(__DIR__ . "/../app/Views/{$this->layoutPath}.phtml")) {
			return require_once __DIR__ . "/../app/Views/{$this->layoutPath}.phtml";
		}else{
			echo "Error: Layout Path not found!";
		}
	}

	protected function setPageTitle($pageTitle)
	{
		$this->pageTitle = $pageTitle;
	}

	protected function getPageTitle($separator = null)
	{
		if($separator){
			return $this->pageTitle . " " . $separator . " ";
		}else{
			return $this->pageTitle;
		}
	}

	protected function request($req)
	{
		$method = $_SERVER['REQUEST_METHOD'];		

		if($method == $req) {
			return true;
		}else {
			return false;
		}
	}

	public function req()
	{
		global $_DELETE; array();
		global $_PUT; array();

		if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE')) {
			parse_str(file_get_contents('php://input'), $_DELETE);
		}
		if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT')) {
			parse_str(file_get_contents('php://input'), $_PUT);
		}

		return ["PUT"=>$_PUT,"DELETE"=>$_DELETE];
	}
}