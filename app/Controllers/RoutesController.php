<?php

namespace App\Controllers;

use Core\BaseController;
use Core\Container;
use Core\Page;

class RoutesController extends BaseController
{
	private $product;
	private $page;

	public function __construct()
	{
		parent::__construct();
		$this->product = Container::getModel("Product");
		$pageData = ["pagetitle"=>"VIME - Micro Frmework PHP","author"=>"Victor Alves","desc"=>"Description"];
		$this->page = new Page([],"website/",$pageData);
	}

	public function index(){

		$this->page->setView("index",[
			"title"=>"VIME PHP",
			"subtitle"=>"Micro Framework PHP (Simple Router)",
			"content"=>"By: Victor Alves Mendes"
		]);		

	}

}