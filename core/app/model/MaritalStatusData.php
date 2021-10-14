<?php
class MaritalStatusData {
	public static $tablename = "marital_status";

	public function __construct(){
        $this->status = "1";
		$this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
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