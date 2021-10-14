<?php
class SpendData {
	public static $tablename = "spend";

	public function __construct(){
        $this->status = "1";
		$this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function getCategory(){ return SpendData::getById($this->category_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (concept_id,amount,bill_id,ncf,date_at,observations,status,user_id,created_at) ";
		$sql .= "values (\"$this->concept_id\",$this->amount,\"$this->bill_id\",\"$this->ncf\",\"$this->date_at\",\"$this->observations\",1,$this->user_id,$this->created_at)";
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
		$sql = "update ".self::$tablename." set concept_id=\"$this->concept_id\",ncf=\"$this->ncf\",bill_id=$this->bill_id,amount=$this->amount,date_at=\"$this->date_at\",observations=\"$this->observations\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update_box(){
		$sql = "update ".self::$tablename." set box_id=$this->box_id,user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public function del_category(){
		$sql = "update ".self::$tablename." set category_id=NULL,user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update_image(){
		$sql = "update ".self::$tablename." set image=\"$this->image\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SpendData());
	}

	public static function getAll(){
		$sql = "select spend.*,c.description from ".self::$tablename." left join concept c on spend.concept_id = c.id order by created_at ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SpendData());
	}

	public static function getAllUnBoxed(){
		$sql = "select spend.*,c.description from ".self::$tablename." left join concept c on spend.concept_id = c.id where box_id is NULL order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SpendData());
	}

	public static function getAllUnBoxedByDate($start,$end){
		$sql = "select spend.*,c.description from ".self::$tablename." left join concept c on spend.concept_id = c.id where box_id is NULL and spend.date_at >= \"$start\" and spend.date_at <= \"$end\" order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SpendData());
	}

	public static function getAllByPage($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id>=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SpendData());
	}

	public static function getLike($p){
		$sql = "select * from ".self::$tablename." where barcode like '%$p%' or name like '%$p%' or id like '%$p%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SpendData());
	}

	public static function getAllByUserId($user_id){
		$sql = "select * from ".self::$tablename." where user_id=$user_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SpendData());
	}

	public static function getAllByCategoryId($category_id){
		$sql = "select * from ".self::$tablename." where category_id=$category_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SpendData());
	}

    public static function getGroupByDateOp($start,$end){
        $sql = "select *,sum(price) as t from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and status=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

}

?>