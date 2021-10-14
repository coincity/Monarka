<?php
class ConceptData {
	public static $tablename = "concept";

	public function __construct(){
        $this->status = "1";
		$this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (description,status,user_id,created_at) ";
		$sql .= "value (\"$this->description\",1,$this->user_id,$this->created_at)";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del($status){
		$sql = "update ".self::$tablename." set status=\"$status\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set description=\"$this->description\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ConceptData());
	}

	public static function getAll(){
		$sql = "select ".self::$tablename.".*,status.description status_dsc from ".self::$tablename."  LEFT JOIN status ON ".self::$tablename.".status=status.id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ConceptData());
	}

	public static function getAllActive(){
		$sql = "select ".self::$tablename.".*,status.description status_dsc from ".self::$tablename."  LEFT JOIN status ON ".self::$tablename.".status=status.id WHERE ".self::$tablename.".status=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ConceptData());
	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where description like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ConceptData());
	}

}

?>