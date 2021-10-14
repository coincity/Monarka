<?php
class CategoryData {
	public static $tablename = "category";

	public function __construct(){
        $this->status = "1";
        $this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (type,prefix,description,user_id,created_at) ";
		$sql .= "value (\"$this->type\",\"$this->prefix\",\"$this->description\",$this->user_id,$this->created_at)";
		Executor::doit($sql);
	}

	public function del($status){
		$sql = "update ".self::$tablename." set status=\"$status\" where id=$this->id";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set type=\"$this->type\",prefix=\"$this->prefix\",description=\"$this->description\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CategoryData());
	}

	public static function getAll(){
		$sql = "select ".self::$tablename.".*,status.description status_dsc from ".self::$tablename."  LEFT JOIN status ON ".self::$tablename.".status=status.id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

	public static function getAllActive(){
		$sql = "select ".self::$tablename.".*,status.description status_dsc from ".self::$tablename."  LEFT JOIN status ON ".self::$tablename.".status=status.id WHERE ".self::$tablename.".status=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

    public static function getByType($type){
		$sql = "select ".self::$tablename.".*,status.description status_dsc from ".self::$tablename."  LEFT JOIN status ON ".self::$tablename.".status=status.id WHERE ".self::$tablename.".status=1 and type=$type";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

	public static function getRepeated($prefix){
		$sql = "select * from ".self::$tablename." where prefix = '$prefix'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

	public static function getRepeatedById($prefix,$id){
		$sql = "select * from ".self::$tablename." where prefix = '$prefix' and id <> $id ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

}

?>