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
			$current_user = $this->user->getFind("email",$_POST['email']);
			if ($current_user) {			
				$key = SECRET;			
				$pass = JWT::encode($_POST['password'], $key);
				if ($pass === $current_user->password) {
					$user_data = [
						"id"=>$current_user->id,
						"name"=>$current_user->name,
						"email"=>$current_user->email
					];
					$user_cokie = JWT::encode($user_data, $key);
					print json_encode(["token"=>$user_cokie]);
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