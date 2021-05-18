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
}