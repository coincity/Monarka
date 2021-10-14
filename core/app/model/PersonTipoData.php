<?php
class PersonTipoData {
	public static $tablename = "person_tipo";

	public function __construct() {
		$this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function add_Tipo() {
		$sql = "insert into person (no,name,lastname,address1,email1,phone1,is_active_access,password,kind,credit_limit,has_credit,user_id,created_at) ";
		$sql .= "value (\"$this->no\",\"$this->name\",\"$this->lastname\",\"$this->address1\",\"$this->email1\",\"$this->phone1\",\"$this->is_active_access\",\"$this->password\",1,\"$this->credit_limit\",$this->has_credit,$this->user_id,$this->created_at)";
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
		$sql = "update ".self::$tablename." set name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getWithoutPacient(){
		$sql = "select * from ".self::$tablename." WHERE id != 1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PersonData());
	}

}

?>