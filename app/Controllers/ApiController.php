<?php

namespace App\Controllers;

use Core\BaseController;
use Core\Container;

class ApiController extends BaseController
{
	private $user;

	public function __construct()
	{
		parent::__construct();
		$this->user = Container::getModel("User");
	}

	public function index()
	{
        print("GET");
	}

	public function store()
	{
		print("POST");
	}

	public function update()
	{
		print("PUT");
	}

	public function destroy()
	{
		print("DELETE");
	}

}