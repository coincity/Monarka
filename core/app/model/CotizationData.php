<?php
class CotizationData {
	public static $tablename = "cotizations";

	public function __construct(){
        $this->status = "1";
        $this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function getPerson(){ return PersonData::getById($this->person_id);}
	public function getUser(){ return UserData::getById($this->user_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (ref_id,person_id,subtotal,discount,taxes,total,status,user_id,created_at) ";
		$sql .= "value ($this->ref_id,$this->person_id,$this->subtotal,$this->discount,$this->taxes,$this->total,1,$this->user_id,$this->created_at)";
		return Executor::doit($sql);
	}

    public function update_ref_id(){
		$sql = "update ".self::$tablename." set ref_id=\"$this->ref_id\" where id=$this->id";
		return Executor::doit($sql);
	}

    public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
        $sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new CotizationData());
	}

	public static function getAll(){
		$sql = "select ".self::$tablename.".*,person.name,person.lastname from ".self::$tablename." inner join person on ".self::$tablename.".person_id = person.id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CotizationData());
	}

    public static function getActive(){
		$sql = "select ".self::$tablename.".*,person.name,person.lastname from ".self::$tablename." inner join person on ".self::$tablename.".person_id = person.id and ".self::$tablename.".status = 1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CotizationData());
	}

	public static function getAllByDate($start,$end){
        $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CotizationData());
	}

	public static function getAllByDateOpByUserId($user,$start,$end){
        $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new CotizationData());
	}

}

?>