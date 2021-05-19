<?php

namespace App\Controllers;

use Core\BaseController;
use Core\Container;

class ApiController extends BaseController
{
	private $product;

	public function __construct()
	{
		parent::__construct();
		$this->product = Container::getModel("Product");
	}

	public function index(){

        $this->request('GET') &&
            print("GET");

		$this->request('POST') &&
            print("POST");

		$this->request('PUT') &&
            print("PUT");

		$this->request("DELETE") &&
            print("DELETE");

	}

}