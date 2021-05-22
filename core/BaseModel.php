<?php

namespace Core;

use PDO;
use Core\DataBase;
use Core\Redirect;
use Core\Session;

class BaseModel
{
	private $pdo;
	protected $table;

	public function __construct()
	{
		// $this->pdo = DataBase::connect();
	}

	public function select($fields, $params = "", $values = [])
	{
		$query = "SELECT {$fields} FROM {$this->table} {$params}";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$stmt->execute($values);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		DataBase::disconnect();
		return $result;
	}

	public function selectAssoc($fields, $params = "", $values = [])
	{
		$query = "SELECT {$fields} FROM {$this->table} {$params}";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$stmt->execute($values);
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		DataBase::disconnect();
		return $result;
	}

	public function getFind($campo,$value)
	{
		$query = "SELECT * FROM {$this->table} WHERE {$campo} = ?";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$stmt->execute(array($value));
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		DataBase::disconnect();
		return $result;
	}

	public function create($campos,$param,$values)
	{
		$query = "INSERT INTO {$this->table} ({$campos}) VALUES ({$param})";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$result = $stmt->execute($values);
		DataBase::disconnect();
		return $result;
	}

	public function update($set,$request)
	{
		$query = "UPDATE {$this->table} SET {$set} WHERE id = ?";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$result = $stmt->execute($request);
		DataBase::disconnect();
		return $result;
	}

	public function updateWhere($set,$request,$where)
	{
		$query = "UPDATE {$this->table} SET {$set} WHERE $where = ?";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$result = $stmt->execute($request);
		DataBase::disconnect();
		return $result;
	}

	public function delete($id)
	{
		$query = "DELETE FROM {$this->table} WHERE id=:id";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$stmt->bindValue(":id", $id);
		$result = $stmt->execute();
		DataBase::disconnect();
		return $result;
	}

	public function timezone()
	{
		date_default_timezone_set("America/Sao_Paulo");
		setlocale(LC_ALL, 'pt_BR');
	}

	public function hello()
	{
		$this->timezone();

		$hr = date(" H ");
		if($hr >= 12 && $hr<18) {
		    $resp = "Boa tarde";}
		else if ($hr >= 06 && $hr <12 ){
		    $resp = "Bom dia";}
		else {
		    $resp = "Boa noite";}

		    return $resp;
	}

	public function kickout($session){
		if(!Session::get($session)){
			Redirect::route('/login',['error'=>['Oops, você não está logado!']]);
		}
	}

	public function alert()
	{
		$alert = 0;
		$message = null;		
		$icon = null;		
		if(Session::get('success')){
			$alert = 1;
			$icon = "success";
			$message = Session::get('success');
			Session::destroy('success');
		}
		if(Session::get('error')){
			$alert = 1;
			$icon = "error";
			$message = Session::get('error');
			Session::destroy('error');
		}
		if(Session::get('info')){
			$alert = 1;
			$icon = "info";
			$message = Session::get('info');
			Session::destroy('info');
		}
		if(Session::get('warning')){
			$alert = 1;
			$icon = "warning";
			$message = Session::get('warning');
			Session::destroy('warning');
		}

		return ["alert"=>$alert,"message"=>$message,"icon"=>$icon];
	}

	public function upload($img){

		if(isset($img['name']) && $img["error"] == 0)
		{
		    $arquivo_tmp = $img['tmp_name'];
		    $nome = $img['name'];
		    $extensao = strrchr($nome, '.');
		    $extensao = strtolower($extensao);

		    if(strstr('.jpg;.jpeg;.gif;.png', $extensao))
		    {
		        $novoNome = md5(microtime()) . '-'.str_replace(" ","",$nome);
		        $destino = 'assets/uploads/' . $novoNome;
		        move_uploaded_file( $arquivo_tmp, $destino);
		    }
		    else
		    {
		        print"Você poderá enviar apenas arquivos \'*.jpg;*.jpeg;*.gif;*.png\'";
		        	exit;
		    }
		}
		else
		{
	        print"Você não enviou nenhum arquivo!";
	        	exit;
		} 

		return $novoNome;		
	}

	public function uploadBase64()
	{
		define('UPLOAD_DIR', 'assets/uploads/');
		$img = $_POST['image'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = UPLOAD_DIR . uniqid() . '.png';
		$success = file_put_contents($file, $data);
		print $success ? $file : 'Unable to save the file.';
		return $file;
	}

	public function setPaginator($n,$campo = null)
	{
		$itens_por_pagina = $n;
		if (isset($_GET['pagina'])){
			$pagina = intval($_GET['pagina']) * $itens_por_pagina;
		}else{
			$pagina = 0 * $itens_por_pagina;
		}		
		$query = "SELECT * FROM {$this->table} ORDER BY {$campo} DESC LIMIT {$pagina}, {$itens_por_pagina}";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		DataBase::disconnect();
		return $result;
	}

	public function setPaginatorWhere($n,$campo,$where,$value)
	{
		$itens_por_pagina = $n;
		$pagina = intval($_GET['pagina']) * $itens_por_pagina;
		$query = "SELECT * FROM {$this->table} WHERE {$where} = ? ORDER BY {$campo} DESC LIMIT {$pagina}, {$itens_por_pagina}";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$stmt->execute(array($value));
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		DataBase::disconnect();
		return $result;
	}

	public function setPaginatorAssoc($n)
	{
		$itens_por_pagina = $n;
		if (isset($_GET['pagina'])){
			$pagina = intval($_GET['pagina']) * $itens_por_pagina;
		}else{
			$pagina = 0 * $itens_por_pagina;
		}		
		$query = "SELECT * FROM {$this->table} LIMIT {$pagina}, {$itens_por_pagina}";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		DataBase::disconnect();
		return $result;
	}

	public function setPaginatorWhereAssoc($n,$campo,$where,$value)
	{
		$itens_por_pagina = $n;
		if (isset($_GET['pagina'])){
			$pagina = intval($_GET['pagina']) * $itens_por_pagina;
		}else{
			$pagina = 0 * $itens_por_pagina;
		}
		$query = "SELECT * FROM {$this->table} WHERE {$where} = ? ORDER BY {$campo} DESC LIMIT {$pagina}, {$itens_por_pagina}";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$stmt->execute(array($value));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		DataBase::disconnect();
		return $result;
	}

	public function getPaginator()
	{
		if(isset($_GET['pagina'])){
			return $pagina = intval($_GET['pagina']);	
		}else{
			return 0;
		}		
	}

	//Remover Caracteres especiais
	public function caracterSpecialRemove($string) {

	    // matriz de entrada
	    $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );

	    // matriz de saída
	    $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','a','a','e','i','o','u','n','n','c','c','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-' );

	    // devolver a string
	    return str_replace($what, $by, $string);
	}

	public function get_ip()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

}
