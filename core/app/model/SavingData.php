<?php
class SavingData {
	public static $tablename = "saving";

	public function __construct(){
        $this->status = "1";
        $this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (concept_id,description,date_at,amount,kind,status,user_id,created_at) ";
		$sql .= "value ($this->concept_id,\"$this->description\",\"$this->date_at\",\"$this->amount\",\"$this->kind\",1,$this->user_id,$this->created_at)";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set concept_id=$this->concept_id,description=\"$this->description\",date_at=\"$this->date_at\",amount=\"$this->amount\",kind=\"$this->kind\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public function updateById($k,$v){
		$sql = "update ".self::$tablename." set $k=\"$v\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		 $sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SavingData());
	}

	public static function getBy($k,$v){
		$sql = "select * from ".self::$tablename." where $k=\"$v\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SavingData());
	}

	public static function sumByKind($k){
		$sql = "select sum(amount) as s from ".self::$tablename." where kind=\"$k\" and status = 1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SavingData());
	}

	public static function getAll(){
        $sql = "select ".self::$tablename.".*,concept.description as concept from ".self::$tablename." INNER JOIN concept on ".self::$tablename.".concept_id = concept.id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SavingData());
	}

	public static function getAllByKind($k){
		 $sql = "select * from ".self::$tablename." where kind=$k order by date_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SavingData());
	}

	public static function getAllByKindDate($d,$k){
		 $sql = "select * from ".self::$tablename." where kind=$k and date_at=\"$d\" order by date_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SavingData());
	}

	public static function getSumByKindDate($d,$k){
		$sql = "select sum(amount) as t from ".self::$tablename." where kind=$k and date_at=\"$d\" order by date_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SavingData());
	}

	public static function getAllBy($k,$v){
		 $sql = "select * from ".self::$tablename." where $k=\"$v\"";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SavingData());
	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SavingData());
	}

}

?>