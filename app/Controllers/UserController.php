<?php

namespace App\Controllers;

use Core\BaseController;
use Core\Container;
use Firebase\JWT\JWT;

class UserController extends BaseController
{
	private $user;

	public function __construct()
	{
		parent::__construct();
		$this->user = Container::getModel("User");
	}

	public function index(){

        $this->request('GET') &&
            $this->findAll();

		$this->request('POST') &&
            $this->store();

	}

	public function findAll()
	{
		$users = $this->user->all();

		if($users) {
			print json_encode($users);
			http_response_code(200);
		}else {
			print json_encode(["error"=>"Ops, algo deu errado..."]);
			http_response_code(200);
		}
	}

	public function store() {

		$key = SECRET;		

		$exist_user = $this->user->getFind("email",$_POST['email']);

		if($exist_user) {
			print json_encode(["error"=>"Esse e-mail já está cadastrado!"]);
			http_response_code(406);
		}else {
			$set = "name, email, password, role";
			$param = "?, ?, ?, ?";
			$password = JWT::encode($_POST['password'], $key);
			$values = [$_POST['name'],$_POST['email'],$password,'0'];

			if($this->user->create($set, $param, $values)) {
				print json_encode(["success"=>"Usuário cadastrado com sucesso!"]);
				http_response_code(200);
			}else {
				print json_encode(["error"=>"Não foi possível efetuar o cadastro..."]);
				http_response_code(400);
			}
		}

	}

}