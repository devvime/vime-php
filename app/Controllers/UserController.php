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

	public function index()
	{
        $this->request('GET') &&
            $this->users();

		$this->request('POST') &&
            $this->store();

		$this->request('DELETE') &&
            $this->destroy($this->req()['DELETE']['id']);

			$this->request('PUT') &&
				$this->update($this->req()['PUT']);

	}

	public function users()
	{
		$users = $this->user->select("id, name, email");

		if($users) {
			print json_encode($users);
			http_response_code(200);
		}else {
			print json_encode(["error"=>"Ops, algo deu errado..."]);
			http_response_code(400);
		}
	}

	public function store() {

		$key = SECRET;
		
		if (!isset($_POST['name'])) {
			print json_encode(["error"=>"O Campo nome é obrigatório"]);
			http_response_code(406);
		}else if (!isset($_POST['email'])) {
			print json_encode(["error"=>"O Campo e-mail é obrigatório"]);
			http_response_code(406);
		}else if (!isset($_POST['password'])) {
			print json_encode(["error"=>"O Campo Senha é obrigatório"]);
			http_response_code(406);
		} else {
			$exist_user = $this->user->select("*","WHERE email = ?",[$_POST['email']]);

			if($exist_user) {
				print json_encode(["error"=>"Esse e-mail já está cadastrado!"]);
				http_response_code(406);
				exit;
			}else {
				$set = "name, email, password, role";
				$param = "?, ?, ?, ?";
				$password = JWT::encode($_POST['password'], $key);
				$values = [$_POST['name'],$_POST['email'],$password,'0'];

				if($this->user->create($set, $param, $values)) {
					print json_encode(["success"=>"Cadastro realizado com sucesso!"]);
					http_response_code(200);
				}else {
					print json_encode(["error"=>"Não foi possível efetuar o cadastro..."]);
					http_response_code(400);
				}
			}
		}	
	}

	public function read($id)
	{
		$user = $this->user->select("id, name, email", "WHERE id = ?", [$id]);

		if ($user) {
			print json_encode($user);
			http_response_code(200);
		}else {
			print json_encode(["error"=>"Usuário não encontrado..."]);
			http_response_code(404);
		}
	}

	public function destroy($id)
	{
		if (!isset($id)) {
			print json_encode(["error"=>"Informe o id do usuário a ser deletado!"]);
			http_response_code(406);
		}else {
			if($this->user->select("id, name, email", "WHERE id = ?",[$id])) {
				if ($this->user->delete($id)) {
					print json_encode(["success"=>"Usuário deletado com sucesso!"]);
					http_response_code(200);
				}else {
					print json_encode(["error"=>"Não foi possível deletar o usuário..."]);
					http_response_code(400);
				}
			}else {
				print json_encode(["error"=>"Usuário inexistente, não foi possível deletar."]);
				http_response_code(404);
			}
		}				
	}

	public function update($data)
	{
		if (!isset($data['id'])) {
			print json_encode(["error"=>"Informe o id do usuário a ser editado!"]);
			http_response_code(406);
		}else {
			$current_user = $this->user->select('*','WHERE id = ?',[$data['id']]);
			if ($current_user) {
				if (isset($data['password'])) {
					$data['password'] = JWT::encode($data['password'], SECRET);
				}
				if ($this->user->update("name = ?, email = ?, password = ?",$data, "WHERE id = ?", [$data['id']])) {
					print json_encode(["success"=>"Usuário atualizado com sucesso!"]);
					http_response_code(200);
				}else {
					print json_encode(["error"=>"Não foi possível atualizar o usuário..."]);
					http_response_code(400);
				}
			}else {
				print json_encode(["error"=>"Usuário solicitado não foi encontrado!"]);
				http_response_code(404);
			}
		}
	}

}