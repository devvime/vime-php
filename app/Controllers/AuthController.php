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
		$this->json();
		$this->cors();
	}

	public function index(){
        echo json_encode(["error"=>"Acesso não autorizado!"]);
        http_response_code(406);
		die;
	}

	public function auth() {		
		$current_user = $this->user->select("*","where email = ?",[$_POST['email']]);
		if ($current_user) {
			$user = $current_user[0];
		}else {
			echo json_encode(["error"=>"Usuário ou senha incorreto."]);
			die;
		}			
		if ($user) {			
			$pass = JWT::encode($_POST['password'], SECRET);
			if ($pass === $user->password) {
				$user_data = [
					"id"=>$user->id,
					"name"=>$user->name,
					"email"=>$user->email
				];
				$user_cokie = JWT::encode($user_data, SECRET);
				echo json_encode(["token"=>$user_cokie]);
				die;
			}else {
				echo json_encode(["error"=>"Senha incorreta!"]);
				die;
			}			
		}else {
			echo json_encode(["error"=>"Usuário não encontrato."]);
			die;
		}		
	}

	public function verifyAuthToken()
	{
		$token = $this->getAuthorizationHeader();
		$pass = explode(' ', $token);
		if (isset($pass[1])) {	
			$data = JWT::decode($pass[1], SECRET, array('HS256'));
			if ($data) {
				echo json_encode(["data"=>$data]);
				die;
			}else {
				echo json_encode(["error"=>"Não autorizado!"]);
				die;
			}				
		}else {
			echo json_encode(["error"=>"Não autorizado!"]);
			die;
		}
	}
}