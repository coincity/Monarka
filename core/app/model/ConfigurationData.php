<?php
class ConfigurationData {
	public static $tablename = "configuration";

	public function __construct(){
        $this->status = "1";
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (name,short_name,is_active) ";
		$sql .= "value (\"$this->name\",\"$this->short_name\",$this->is_active)";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",short_name=\"$this->short_name\",is_active=\"$this->is_active\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function updateValFromName($name,$val){
		$sql = "update ".self::$tablename." set val=\"$val\" where short=\"$name\"";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ConfigurationData());
	}

	public static function getByPreffix($id){
		$sql = "select * from ".self::$tablename." where short=\"$id\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ConfigurationData());
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename." order by id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ConfigurationData());
	}

	public static function getPublics(){
		$sql = "select * from ".self::$tablename." where is_active=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ConfigurationData());
	}

}

?>
