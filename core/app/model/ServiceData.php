<?php
class ServiceData {
	public static $tablename = "services";

	public function __construct(){
        $this->status = "1";
		$this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function getCategory(){ return CategoryData::getById($this->category_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (category_id, barcode, name, description, price, itbis, status, user_id,created_at)";
		$sql .= "value ($this->category_id,\"$this->barcode\",\"$this->name\",\"$this->description\",\"$this->price\",$this->itbis,$this->status,$this->user_id,NOW())";
		return Executor::doit($sql);
	}
	public function add_with_image(){
		$sql = "insert into ".self::$tablename." (category_id, image, barcode, name, description, price, itbis, status, user_id, created_at)";
		$sql .= "value ($this->category_id,\"$this->image\",\"$this->barcode\",\"$this->name\",\"$this->description\",\"$this->price\",$this->itbis, $this->status,$this->user_id,NOW())";
		return Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set barcode=\"$this->barcode\",name=\"$this->name\",price=\"$this->price\",itbis = $this->itbis, category_id=$this->category_id,description=\"$this->description\",user_id=$this->user_id where id=$this->id";
		return Executor::doit($sql);
	}

	public function update_image(){
		$sql = "update ".self::$tablename." set image=\"$this->image\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update_prices(){
		$sql = "update ".self::$tablename." set price_in=\"$this->price_in\",price_out=\"$this->price_out\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ServiceData());
	}

	public static function getServiceById($id){
		$sql = "SELECT 'Servicio' as tipo,id,barcode,name,description,total,1 as inventary_min,category_id FROM ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ServiceData());
	}

	public static function CountByCategory($k){
		$sql = "select count(id) as s from ".self::$tablename." where category_id=\"$k\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ServiceData());
	}

	public static function getAll(){
		$sql = "select ".self::$tablename.".*,status.description status_dsc,category.description category from ".self::$tablename."  LEFT JOIN status ON ".self::$tablename.".status=status.id INNER JOIN category ON ".self::$tablename.".category_id=category.id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ServiceData());
	}

	public static function getAllActive(){
		$sql = "select *from ".self::$tablename." WHERE status = 1 order by name";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ServiceData());
	}

	public static function getAllByCategoryId($id){
		$sql = "select * from ".self::$tablename." where category_id=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ServiceData());
	}

	public static function getLike($p){
		$sql = "select * from ".self::$tablename." where barcode like '%$p%' or name like '%$p%' or id like '%$p%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

}

?>