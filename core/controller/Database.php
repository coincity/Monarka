<?php
class Database {
	public static $db;
	public static $con;
	function __construct(){
		global $config;
		$this->user=$config["Database"]["Username"];
		$this->pass=$config["Database"]["Password"];
		$this->host=$config["Database"]["Host"];
		$this->database_schema=$config["Database"]["Schema"];
	}

	function connect(){
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$con = new mysqli($this->host,$this->user,$this->pass,$this->database_schema);
		$con->query("set sql_mode='';");
		$con->set_charset("utf8");
		return $con;
	}

	public static function getCon(){
		if(self::$con==null && self::$db==null){
			self::$db = new Database();
			self::$con = self::$db->connect();
		}
		return self::$con;
	}
}
?>
