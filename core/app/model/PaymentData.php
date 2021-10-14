<?php
class PaymentData {
	public static $tablename = "payment";

	public function __construct(){
        $this->status = "1";
        $this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function getClient(){ return PersonData::getById($this->person_id); }
	public function getPaymentType(){ return PaymentTypeData::getById($this->payment_type_id); }
    public function getPerson(){ return PersonData::getById($this->person_id);}
	public function getUser(){ return UserData::getById($this->user_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (person_id,sell_id,user_id,val,payment_type_id,created_at) ";
		$sql .= "values (\"$this->person_id\",$this->sell_id,$this->user_id,$this->val,1,$this->created_at)";
		return Executor::doit($sql);
	}


	public function add_payment(){
		$sql = "insert into ".self::$tablename." (person_id,sell_id,user_id,val,payment_type_id,saldada,created_at) ";
		$sql .= "values (\"$this->person_id\",$this->sell_id,$this->user_id,$this->val,2,$this->saldada,NOW())";
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

	public function update_saldo(){
		$sql = "update ".self::$tablename." set saldada=1,user_id=".$_SESSION["user_id"]." where person_id=$this->person_id";
		Executor::doit($sql);
	}

	public function saldar(){
		$sql = "update ".self::$tablename." set saldada=1,user_id=".$_SESSION["user_id"]." where person_id=$this->person_id and sell_id=$this->sell_id";
		return Executor::doit($sql);
	}

	public static function getById($id){
		 $sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PaymentData());
	}

	public static function getBySellId($id){
		 $sql = "select * from ".self::$tablename." where sell_id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PaymentData());
	}

	public static function getByName($name){
		 $sql = "select * from ".self::$tablename." where name=\"$name\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PaymentData());
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename." order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PaymentData());
	}

	public static function getAllAbono(){
		$sql = "select * from ".self::$tablename." where payment_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PaymentData());
	}

	public static function getAllAbonoByUser($id){
		$sql = "select * from ".self::$tablename." where payment_type_id=2 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PaymentData());
	}

	public static function getAllByDate($start,$end){
		$sql = "select * from ".self::$tablename." where (date(created_at)>=\"$start\" and date(created_at)<=\"$end\") and payment_type_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PaymentData());
	}

	public static function getAllByDateAndUser($start,$end,$id){
		$sql = "select * from ".self::$tablename." where (date(created_at)>=\"$start\" and date(created_at)<=\"$end\") and payment_type_id=2 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PaymentData());
	}

	public static function getAllByDateAndClient($start,$end,$id){
		$sql = "select * from ".self::$tablename." where (date(created_at)>=\"$start\" and date(created_at)<=\"$end\") and payment_type_id=2 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PaymentData());
	}

	public static function getAllByClientId($id){
		$sql = "select * from ".self::$tablename." where person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PaymentData());
	}

	public static function sumByClientId($id){
		$sql = "SELECT COALESCE(SUM(val), 0) as total from ".self::$tablename." where person_id=$id and saldada=0";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PaymentData());
	}

	public static function getOldSellId($id){
		$sql = "SELECT * from (select SUM(val) as total,sell_id from ".self::$tablename." where person_id=$id and saldada=0 GROUP by sell_id) as subquery WHERE subquery.total <> 0";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PaymentData());
	}

	public static function sumPaymentByClientId($id){
		$sql = "SELECT COALESCE(SUM(val), 0) as total from ".self::$tablename." where sell_id=$id and payment_type_id=2";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PaymentData());
	}

	public static function sumPaymentByPendingId($id){
		$sql = "SELECT COALESCE(SUM(val), 0) as total from ".self::$tablename." where sell_id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PaymentData());
	}

	public static function sumByClientBySellId($id,$sell_id){
		$sql = "SELECT COALESCE(SUM(val), 0) as total from ".self::$tablename." where person_id=$id and sell_id=$sell_id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PaymentData());
	}

	public static function sumByClientByCreditId($id,$sell_id){
		$sql = "select COALESCE(SUM(val), 0) as total from ".self::$tablename." where person_id=$id and sell_id=$sell_id and saldada=0";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PaymentData());
	}
}

?>