<?php

namespace App\Controllers;

use Core\BaseController;
use Core\Container;
use Core\Page;

class HomeController extends BaseController
{
	private $home;
	private $page;

	public function __construct()
	{
		parent::__construct();
		$this->home = Container::getModel("Home");
		$pageData = ["pagetitle"=>"VIME - Micro Frmework PHP","author"=>"Victor Alves","desc"=>"Description"];
		$this->page = new Page([],"website/",$pageData);
	}

	public function index(){

		$this->page->setView("index",[
			"title"=>"VIME",
			"subtitle"=>"Micro Framework PHP",
			"content"=>"By: Victor Alves Mendes"
		]);

	}

}