<?php

namespace App\Controllers;

use Core\BaseController;
use Core\Container;
use Firebase\JWT\JWT;

class AuthController extends BaseController
{
	private $user;

	public function __construct()
	{
		parent::__construct();
		$this->user = Container::getModel("User");
	}

	public function index(){

        $this->request('GET') &&
            print json_encode(["error"=>"Acesso não autorizado!"]);
            http_response_code(406);
        
            $this->request('POST') &&
                $this->auth();

	}

	public function auth() {
		if ($this->request('POST')) {
			$current_user = $this->user->select("*","where email = ?",[$_POST['email']]);
			$user = $current_user[0];
			if ($user) {			
				$pass = JWT::encode($_POST['password'], SECRET);
				if ($pass === $user->password) {
					$user_data = [
						"id"=>$user->id,
						"name"=>$user->name,
						"email"=>$user->email
					];
					$user_cokie = JWT::encode($user_data, SECRET);
					print json_encode(["token"=>$user_cokie]);
					http_response_code(200);
				}else {
					print json_encode(["error"=>"Senha incorreta!"]);
					http_response_code(403);
				}			
			}else {
				print json_encode(["error"=>"Usuário não encontrato."]);
				http_response_code(404);
			}
		}else {
			print json_encode(["error"=>"Você não está logado"]);
			http_response_code(401);
		}
	}
}