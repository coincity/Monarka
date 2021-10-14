<?php
class StockData {
	public static $tablename = "stock";

	public function __construct(){
		$this->name = "";
		$this->lastname = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
        $this->created_at = "NOW()";
	}

	public function add(){
		$sql = "insert into ".self::$tablename." () ";
		$sql .= "value ()";
		return Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new StockData());
	}

	public static function getPrincipal(){
	    $sql = "select max(id) as id from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::one($query[0],new StockData());
	}


}

?>