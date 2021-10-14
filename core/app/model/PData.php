<?php
class PData {
	public static $tablename = "p";

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PData());
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new PData());
	}
	
	public static function getSome(){
		$sql = "select * from ".self::$tablename." where id in (1,4)";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PData());
	}
	
	public static function getBuy(){
		$sql = "select * from ".self::$tablename." where id in (1,4)";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PData());
	}
}

?>