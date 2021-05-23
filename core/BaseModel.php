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

	public function create($campos,$param,$values)
	{
		$query = "INSERT INTO {$this->table} ({$campos}) VALUES ({$param})";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$result = $stmt->execute($values);
		DataBase::disconnect();
		return $result;
	}

	public function update($fields, $values, $condition = "")
	{
		$set_values = implode(",",$values);
		$get_values = explode(",",$set_values);
		$query = "UPDATE {$this->table} SET {$fields} {$condition}";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);		
		$result = $stmt->execute($get_values);
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

	public function setPagination($n,$campo = null)
	{
		$items_per_page = $n;
		if (isset($_GET['pagina'])){
			$page = intval($_GET['pagina']) * $items_per_page;
		}else{
			$page = 0 * $items_per_page;
		}		
		$query = "SELECT * FROM {$this->table} ORDER BY {$campo} DESC LIMIT {$page}, {$items_per_page}";
		$this->pdo = DataBase::connect();
		$stmt = $this->pdo->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		DataBase::disconnect();
		return $result;
	}

	public function getPagination()
	{
		if(isset($_GET['pagina'])){
			return $page = intval($_GET['pagina']);	
		}else{
			return 0;
		}		
	}

}
