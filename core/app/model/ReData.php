<?php
class ReData {
	public static $tablename = "re";

	public function __construct(){
        $this->created_at = "NOW()";
		$this->ref_id=NULL;
		$this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function getPerson(){ return PersonData::getById($this->person_id);}
	public function getUser(){ return UserData::getById($this->user_id);}
	public function getP(){ return PData::getById($this->p_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (ref_id,bill_id,person_id,p_id,total,paid,itbis,status,user_id,created_at,ncf) ";
		$sql .= "values ($this->ref_id,$this->bill_id,$this->person_id,$this->p_id,$this->total,$this->paid,$this->itbis,1,$this->user_id,$this->created_at,'$this->ncf')";
		return Executor::doit($sql);
	}

	public function add_de(){
		$sql = "insert into ".self::$tablename." (status,stock_to_id,sell_from_id,user_id,operation_type_id,created_at) ";
		$sql .= "value (0,$this->stock_to_id,$this->sell_from_id,$this->user_id,5,$this->created_at)";
		return Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}

	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

	public function update_ref_id(){
		$sql = "update ".self::$tablename." set ref_id=\"$this->ref_id\" where id=$this->id";
		return Executor::doit($sql);
	}

	public function update_p(){
		$sql = "update ".self::$tablename." set p_id=$this->p_id where id=$this->id";
		return Executor::doit($sql);
	}

	public function update_payment(){
		$sql = "update ".self::$tablename." set p_id=$this->p_id and cash=$this->cash where id=$this->id";
		return Executor::doit($sql);
	}

	public static function getById($id){
		 $sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ReData());
	}

	public function cancel(){
		$sql = "update ".self::$tablename." set d_id=3,p_id=3 where id=$this->id";
		return Executor::doit($sql);
	}

	public static function getCredits(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getCreditsByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function CountByCategory($id){
		$sql = "select sum(total) as s from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ServiceData());
	}

	public static function getCreditsByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getSellsByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getSellsUnBoxed(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id is NULL and p_id=1 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getRes(){
		$sql = "select ".self::$tablename.".*,person.name,person.lastname,person.company,p.name as 'pdesc'  from ".self::$tablename." inner join person on ".self::$tablename.".person_id = person.id inner join p on ".self::$tablename.".p_id = p.id";;
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

    public static function getPaymentRes(){
		$sql = "select ".self::$tablename.".*,person.name,person.lastname,p.name as 'pdesc'  from ".self::$tablename." inner join person on ".self::$tablename.".person_id = person.id inner join p on ".self::$tablename.".p_id = p.id WHERE ".self::$tablename.".p_id in (2,3)";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getResByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=1 and d_id=1 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getResToPay(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=2  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getResToPayByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=2 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getResToReceive(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and d_id=2  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getResToReceiveByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and d_id=2 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getSQL($sql){
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}


	public static function getAllBySQL($sqlextra){
		$sql = "select * from ".self::$tablename." $sqlextra";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getAllByDateOp($start,$end,$op){
	  $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and is_draft=0 and p_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getAllByOp($start,$end,$op){
	  $sql = "select * from ".self::$tablename." where operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getAllByDateOpByUserId($user,$start,$end,$op){
	  $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}

	public static function getAllByOpByUserId($user,$start,$end,$op){
	  $sql = "select * from ".self::$tablename." where operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}


		public static function getGroupByDateOp($start,$end,$op){
  $sql = "select id,sum(total) as tot,discount,sum(total-discount) as t,count(*) as c from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and p_id not in (3)";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());
	}


	public static function getAllByDateBCOp($clientid,$start,$end,$op){
 		$sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and person_id=$clientid  and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());

	}

	public static function getAllByBCOp($clientid,$start,$end,$op){
 		$sql = "select * from ".self::$tablename." where person_id=$clientid  and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());

	}

	public static function getAllByDateBCOpByUserId($user,$clientid,$start,$end,$op){
 		$sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and person_id=$clientid  and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());

	}

	public static function getAllByBCOpByUserId($user,$clientid,$start,$end,$op){
 		$sql = "select * from ".self::$tablename." where person_id=$clientid  and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ReData());

	}


}

?>