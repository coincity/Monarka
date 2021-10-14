<?php
class WorkshopData {
	public static $tablename = "workshop";

	public function __construct(){
        $this->status = "1";
		$this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (description,brand,model,serie,date_in,date_out,returned,observation,status,user_id,created_at,client_id) ";
		$sql .= "value (\"$this->description\",\"$this->brand\",\"$this->model\",\"$this->serie\",\"$this->date_in\",\"$this->date_out\",\"$this->returned\",\"$this->observation\",1,$this->user_id,$this->created_at,$this->client_id)";
		Executor::doit($sql);
	}

	public function del($status){
		$sql = "update ".self::$tablename." set status=\"$status\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set description=\"$this->description\",brand=\"$this->brand\",model=\"$this->model\",serie=\"$this->serie\",date_in=\"$this->date_in\",date_out=\"$this->date_out\",returned=\"$this->returned\",observation=\"$this->observation\",user_id=$this->user_id,client_id=$this->client_id,status=$this->status where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new WorkshopData());
	}

	public static function getAll(){
		$sql = "select ".self::$tablename.".*, p.name, p.phone,status.description status_dsc from ".self::$tablename."  LEFT JOIN status ON ".self::$tablename.".status=status.id LEFT JOIN person as p on p.id = client_id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new WorkshopData());
	}

	public static function getAllActive(){
		$sql = "select ".self::$tablename.".*,status.description status_dsc from ".self::$tablename."  LEFT JOIN status ON ".self::$tablename.".status=status.id WHERE ".self::$tablename.".status=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new workshopData());
	}

    public static function getByType($type){
		$sql = "select category.*,status.description status_dsc from ".self::$tablename."  LEFT JOIN status ON category.status=status.id WHERE category.status=1 and type=$type";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CategoryData());
	}

}

?>