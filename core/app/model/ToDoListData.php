<?php
class ToDoListData {
	public static $tablename = "todolist";

	public function __construct(){
		$this->created_at = "NOW()";
	}

	public function getFrom(){ return UserData::getById($this->user_from);}
	public function getTo(){ return UserData::getById($this->user_to);}

	public function add(){
		$sql = "insert into ".self::$tablename." (description, user_from, user_to, created_at)";
		$sql .= "value (\"$this->description\",\"$this->user_from\",\"$this->user_to\",$this->created_at)";
		Executor::doit($sql);
	}

	public function del(){
		$sql = "update ".self::$tablename." set status = 0 where id=$this->id";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set description=\"$this->description\", user_from=\"$this->user_from\", user_to=\"$this->user_to\" where id=$this->id";
		Executor::doit($sql);
	}

	public function completed(){
		$sql = "update ".self::$tablename." set is_completed=1 where id=$this->id";
		Executor::doit($sql);
	}

	public function uncompleted(){
		$sql = "update ".self::$tablename." set is_completed=0 where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($val){
		 $sql = "select * from ".self::$tablename." where id=$val";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ToDoListData());
	}

	public static function getAll(){
		 $sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new ToDoListData());
	}

	public static function getUnCompletedByUserId($user){
		//$sql = "select ".self::$tablename.".*,user.name,user.lastname from ".self::$tablename." INNER JOIN user on ".self::$tablename.".user_to = user.id where ".self::$tablename.".status=1 and (user_from=$user or user_to=$user) order by is_completed";
		$sql = "select ".self::$tablename.".*,user.name,user.lastname from ".self::$tablename." INNER JOIN user on ".self::$tablename.".user_from = user.id where ".self::$tablename.".status=1 and (user_from=$user or user_to=$user) order by is_completed";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ToDoListData());
	}

}

?>