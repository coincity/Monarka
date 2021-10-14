<?php
class StatusData {
	public static $tablename = "status";

	public function __construct(){
        $this->status = "1";
		$this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (description,belongto,user_id,created_at) ";
		$sql .= "value (\"$this->description\",\"$this->belongto\",$this->user_id,$this->created_at)";
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
		$sql = "update ".self::$tablename." set description=\"$this->description\",belongto=\"$this->belongto\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new StatusData());
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new StatusData());
	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where description like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new StatusData());
	}

	public static function getByBelongTo($q) {
		$sql = "select * from ".self::$tablename." where belongto like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new StatusData());
	}

	public static function getActions($pertenece,$status_actual) {
		$sql = "select * from ".self::$tablename." where belongto like '%$pertenece%' and id not in ($status_actual)";
		$query = Executor::doit($sql);
		return Model::many($query[0],new StatusData());
	}

	public static function getActionsReservations($pertenece,$status_actual) {
		if($status_actual == 7){
			$sql = "select * from ".self::$tablename." where belongto like '%$pertenece%' and id not in (5,6,7,8)";
		}else{
			$sql = "select * from ".self::$tablename." where belongto like '%$pertenece%' and id not in ($status_actual,5)";
		}

		$query = Executor::doit($sql);
		return Model::many($query[0],new StatusData());
	}

	public static function cambiarStatus($tabla, $id, $status_id){

		if($tabla == "person"){

			$sql = "UPDATE $tabla SET status = $status_id WHERE id = $id;";
			Executor::doit($sql);
		}
		else {
			$sql = "UPDATE $tabla SET status = $status_id WHERE id = $id;";
			Executor::doit($sql);
		}
	}

    public static function cambiarStatusByRef($tabla, $id, $status_id){
        $sql = "UPDATE $tabla SET status = $status_id WHERE sell_id = \"$id\"";
		Executor::doit($sql);
	}

}

?>