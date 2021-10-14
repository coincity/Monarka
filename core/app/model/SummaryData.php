<?php
class SummaryData {
	public static $tablename = "payment_summary";

	public function __construct(){
        $this->status = "1";
        $this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (payment_method_id, sell_id, person_id, reference, val, user_id, created_at) ";
		$sql .= "value ($this->payment_method_id,$this->sell_id,$this->person_id,\"$this->reference\",$this->val,$this->user_id,$this->created_at)";
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

	public static function getById($id){
		 $sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SummaryData());
	}

	public static function getBySellId($id){
		 $sql = "select * from ".self::$tablename." where sell_id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SummaryData());
	}

	public static function getByName($name){
		 $sql = "select * from ".self::$tablename." where name=\"$name\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SummaryData());
	}

	public static function sumByClientId($id){
		$sql = "select SUM(val) as total from ".self::$tablename." where sell_id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SummaryData());
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename." order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SummaryData());
	}
}

?>