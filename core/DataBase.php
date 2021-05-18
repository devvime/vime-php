<?php

namespace Core;

use PDO;

class DataBase extends PDO
{
		const DATABASE = false;
		private static $dbName = 'aula001';
		private static $dbHost = 'localhost';
		private static $dbUser = 'root';
		private static $dbPass = '';
		private static $conn = null;
	    
	    public function __construct() 
	    {
	        die('A função Init nao é permitido!');
	    }
	    
	    public static function connect()
	    {
	        if(null == self::$conn)
	        {
	            try
	            {	
					if (DataBase::DATABASE){
						self::$conn =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUser, self::$dbPass); 
					}	                
	            }
	            catch(PDOException $exception)
	            {
	                die($exception->getMessage());
	            }
	        }
	        return self::$conn;
	    }
	    
	    public static function disconnect()
	    {
	        self::$conn = null;
	    }		
}