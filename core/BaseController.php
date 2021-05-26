<?php

namespace Core;

use Firebase\JWT\JWT;

abstract class BaseController
{
	protected $view;
	private $viewPath;
	private $layoutPath;
	private $pageTitle = null;

	public function __construct()
	{
		$this->view = new \stdClass;		
	}

	protected function renderView($viewPath, $layoutPath = null)
	{
		$this->viewPath = $viewPath;
		$this->layoutPath = $layoutPath;
		if ($layoutPath) {
			return $this->layout();
		}else{
			return $this->content();
		}
	}

	protected function content()
	{
		if (file_exists(__DIR__ . "/../app/Views/{$this->viewPath}.phtml")) {
			return require_once __DIR__ . "/../app/Views/{$this->viewPath}.phtml";
		}else{
			echo "Error: View Path not found!";
		}
	}

	protected function layout()
	{
		if (file_exists(__DIR__ . "/../app/Views/{$this->layoutPath}.phtml")) {
			return require_once __DIR__ . "/../app/Views/{$this->layoutPath}.phtml";
		}else{
			echo "Error: Layout Path not found!";
		}
	}

	protected function setPageTitle($pageTitle)
	{
		$this->pageTitle = $pageTitle;
	}

	protected function getPageTitle($separator = null)
	{
		if($separator){
			return $this->pageTitle . " " . $separator . " ";
		}else{
			return $this->pageTitle;
		}
	}

	protected function request($req)
	{
		$method = $_SERVER['REQUEST_METHOD'];		

		if($method == $req) {
			return true;
		}else {
			return false;
		}
	}

	public function req()
	{
		global $_DELETE; array();
		global $_PUT; array();

		if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE')) {
			parse_str(file_get_contents('php://input'), $_DELETE);
		}
		if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT')) {
			parse_str(file_get_contents('php://input'), $_PUT);
		}

		return ["PUT"=>$_PUT,"DELETE"=>$_DELETE];
	}

	public function upload($img, $folder = 'files/uploads/')
	{
		if (isset($img['name']) && $img["error"] == 0) {
		    $file_temp = $img['tmp_name'];
		    $name = $img['name'];
		    $extension = strrchr($name, '.');
		    $extension = strtolower($extension);
		    if (strstr('.jpg;.jpeg;.gif;.png', $extension)) {
		        $new_name = uniqid() . '-'.str_replace(" ","-",$name);
		        $destiny = $folder . $new_name;
		        move_uploaded_file( $file_temp, $destiny);
		    }else {
		        print json_encode(["error"=>"Você poderá enviar apenas arquivos \'*.jpg;*.jpeg;*.gif;*.png\'"]);
		        exit;
		    }
		}else {
	        print json_encode(["error"=>"Você não enviou nenhum arquivo!"]);
	        exit;
		} 
		return $new_name;		
	}

	public function uploadBase64($folder = 'files/uploads/')
	{
		define('UPLOAD_DIR', $folder);
		$img = $_POST['image'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '-', $img);
		$data = base64_decode($img);
		$file = UPLOAD_DIR . uniqid() . '.png';
		$success = file_put_contents($file, $data);
		print $success ? $file : 'Unable to save the file.';
		return $file;
	}
	
	public function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

	public function getBearerToken() 
	{
		$headers = $this->getAuthorizationHeader();
		if (!empty($headers)) {
			if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
				return $matches[1];
			}
		}
		return null;
	}

	public function verifyAuthToken()
	{
		if ($this->getBearerToken()) {
			try {
				$token = JWT::decode($this->getBearerToken(), SECRET, array('HS256'));
			} catch (\Throwable $th) {
				$token = false;
			}
			if ($token) {
				return $token;
			}else {
				print json_encode(["error"=>"Token de autorização inválido!"]);
				http_response_code(401);
				exit;
			}			
		}else {
			print json_encode(["error"=>"Você não está logado!"]);
			http_response_code(401);
			exit;
		}
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

	public function alert()
	{
		$alert = false;
		$message = null;		
		$icon = null;		
		if (Session::get('success')) {
			$alert = true;
			$icon = "success";
			$message = Session::get('success');
			Session::destroy('success');
		}
		if (Session::get('error')) {
			$alert = true;
			$icon = "error";
			$message = Session::get('error');
			Session::destroy('error');
		}
		if (Session::get('info')) {
			$alert = true;
			$icon = "info";
			$message = Session::get('info');
			Session::destroy('info');
		}
		if (Session::get('warning')) {
			$alert = true;
			$icon = "warning";
			$message = Session::get('warning');
			Session::destroy('warning');
		}

		return ["alert"=>$alert,"message"=>$message,"icon"=>$icon];
	}

	public function caracterSpecialRemove($string) 
	{
	    $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );
	    $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','a','a','e','i','o','u','n','n','c','c','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-' );
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