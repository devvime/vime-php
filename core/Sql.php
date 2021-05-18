<?php 

namespace Core;

use Core\DataBase;

class Sql {

    private $conn;
    private $off;

	public function __construct()
	{

		$this->conn = DataBase::connect();

	}

	private function setParams($statement, $parameters = array())
	{

		foreach ($parameters as $key => $value) {
			
			$this->bindParam($statement, $key, $value);

		}

	}

	private function bindParam($statement, $key, $value)
	{

		$statement->bindParam($key, $value);

	}

	public function query($rawQuery, $params = array())
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

        $stmt->execute();

        $this->off = DataBase::disconnect();

	}

	public function select($rawQuery, $params = array())
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $this->off = DataBase::disconnect();

	}

}

 ?>