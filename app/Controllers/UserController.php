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
		$this->timezone();
		$this->json();
		$this->cors();
	}

	public function index()
	{
		$this->verifyAuthToken();
		$users = $this->user->select("id, name, email");
		if($users) {
			echo json_encode($users);
			die;
		}else {
			echo json_encode(["error"=>"Ops, algo deu errado..."]);
			die;
		}
	}

	public function read($id)
	{
		$this->verifyAuthToken();
		$user = $this->user->select("id, name, email", "WHERE id = ?", [$id]);
		if ($user) {
			echo json_encode($user);
			die;
		}else {
			echo json_encode(["error"=>"Usuário não encontrado..."]);
			die;
		}
	}

	public function store() 
	{		
		if (!isset($_POST['name']) || $_POST['name'] === "") {
			echo json_encode(["error"=>"O Campo nome é obrigatório"]);			
			die;
		}else if (!isset($_POST['email']) || $_POST['email'] === "") {
			echo json_encode(["error"=>"O Campo e-mail é obrigatório"]);			
			die;
		}else if (!isset($_POST['password']) || $_POST['password'] === "") {
			echo json_encode(["error"=>"O Campo Senha é obrigatório"]);		
			die;
		} else {
			$exist_user = $this->user->select("*","WHERE email = ?",[$_POST['email']]);
			if($exist_user) {
				echo json_encode(["error"=>"Esse e-mail já está cadastrado!"]);				
				die;
			}else {
				$set = "name, email, password, role, created_at";
				$param = "?, ?, ?, ?, ?";
				$password = JWT::encode($_POST['password'], SECRET);
				$values = [$_POST['name'],$_POST['email'],$password,'0', date("F j, Y, g:i a")];

				if($this->user->create($set, $param, $values)) {
					echo json_encode(["success"=>"Cadastro realizado com sucesso!"]);					
					die;
				}else {
					echo json_encode(["error"=>"Não foi possível efetuar o cadastro..."]);					
					die;
				}
			}
		}	
	}		

	public function update()
	{
		$data = $this->request();
		if (!isset($data['id'])) {
			echo json_encode(["error"=>"Informe o id do usuário a ser editado!"]);
			die;
		}else {
			$current_user = $this->user->select('*','WHERE id = ?',[$data['id']]);
			if ($current_user) {
				if (isset($data['password'])) {
					$data['password'] = JWT::encode($data['password'], SECRET);
				}
				if ($this->user->update("name = ?, email = ?, password = ?",$data, "WHERE id = ?", [$data['id']])) {
					echo json_encode(["success"=>"Usuário atualizado com sucesso!"]);
					die;
				}else {
					echo json_encode(["error"=>"Não foi possível atualizar o usuário..."]);
					die;
				}
			}else {
				echo json_encode(["error"=>"Usuário solicitado não foi encontrado!"]);
				die;
			}
		}
	}

	public function destroy($id)
	{
		if (!isset($id)) {
			echo json_encode(["error"=>"Informe o id do usuário a ser deletado!"]);
			die;
		}else {
			if($this->user->select("id, name, email", "WHERE id = ?",[$id])) {
				if ($this->user->delete($id)) {
					echo json_encode(["success"=>"Usuário deletado com sucesso!"]);
					die;
				}else {
					echo json_encode(["error"=>"Não foi possível deletar o usuário..."]);
					die;
				}
			}else {
				echo json_encode(["error"=>"Usuário inexistente, não foi possível deletar."]);
				die;
			}
		}				
	}

}