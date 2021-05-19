<?php

namespace Core;

use PDO;

class DataBase extends PDO
{
		const DATABASE = USE_DB;		
		private static $dbHost = DB_HOST;
		private static $dbUser = DB_USER;
		private static $dbPass = DB_PASS;
		private static $dbName = DB_NAME;
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