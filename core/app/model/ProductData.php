<?php
class ProductData {
	public static $tablename = "product";

	public function __construct(){
        $this->status = "1";
		$this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public function getCategory(){ return CategoryData::getById($this->category_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (category_id, barcode, name, description, price_in, min_price, max_price, itbis, warranty_at, observations, status,user_id, created_at)";
		$sql .= "values ($this->category_id,\"$this->barcode\",\"$this->name\",\"$this->description\",$this->price_in,$this->min_price,$this->max_price,$this->itbis,\"$this->warranty_at\",\"$this->observations\",$this->status,$this->user_id,NOW())";
		return Executor::doit($sql);
	}
	public function add_with_image(){
		$sql = "insert into ".self::$tablename." (category_id,image,barcode, name, description,price_in, min_price, max_price,itbis, warranty_at, warranty_file, observations, status,user_id, created_at)";
		$sql .= "values ($this->category_id,\"$this->image\",\"$this->barcode\",\"$this->name\",\"$this->description\",$this->price_in,$this->min_price,$this->max_price,$this->itbis,\"$this->warranty_at\",\"$this->warranty_file\",\"$this->observations\",$this->status,$this->user_id,NOW())";
		return Executor::doit($sql);
	}

	public function update(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",price_in=\"$this->price_in\",barcode=\"$this->barcode\",min_price=$this->min_price,max_price=$this->max_price,itbis=$this->itbis,category_id=$this->category_id,description=\"$this->description\",warranty_at=\"$this->warranty_at\",warranty_file=\"$this->warranty_file\",observations=\"$this->observations\",user_id=$this->user_id where id=$this->id";
		return Executor::doit($sql);
	}

	public function update_image(){
		$sql = "update ".self::$tablename." set image=\"$this->image\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update_price(){
		$sql = "update ".self::$tablename." set min_price=\"$this->min_price\",max_price=\"$this->max_price\",price_in=\"$this->price_in\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());
	}

    public static function getAllProductService(){
		$sql = "SELECT 'Producto' as tipo,id,barcode,name,description,category_id FROM product where status = 1 UNION ALL SELECT 'Servicio' as tipo,id,barcode,name,description,category_id FROM services where status = 1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

    public static function getProductServiceById($id){
		$sql = "SELECT 'Producto' as tipo,id,barcode,name,description,price_in,itbis,category_id,created_at FROM product where id=$id and status = 1 UNION ALL SELECT 'Servicio' as tipo,id,barcode,name,description,0 as 'price_in',itbis,category_id,created_at FROM services where id=$id and status = 1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());
	}

	/*public static function getProductById($id){
		$sql = "SELECT 'Producto' as tipo,id,barcode,name,description,total,1 as inventary_min,category_id FROM ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());
	}

	public static function getAllProductServiceNoInput(){
		$sql = "SELECT 'Producto' as tipo,p.id,barcode,p.name,p.description,price_in,total,inventary_min,category_id FROM product p INNER JOIN category ON p.category_id = category.id  where p.status = 1 and category.input = 0  UNION ALL SELECT 'Servicio' as tipo,s.id,barcode,s.name,s.description,service_price as price_in,total,1 as inventary_min,category_id FROM services s INNER JOIN category ON s.category_id = category.id where s.status = 1 and category.input = 0";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}
    */

	public static function CountByCategory($k){
		$sql = "select count(id) as s from ".self::$tablename." where category_id=\"$k\"";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());
	}

	public static function getAll(){
		$sql = "select product.*,status.description status_dsc,category.description category from ".self::$tablename."  INNER JOIN status ON product.status=status.id INNER JOIN category ON ".self::$tablename.".category_id=category.id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getActive(){
		$sql = "select product.*,status.description status_dsc from ".self::$tablename."  LEFT JOIN status ON product.status=status.id WHERE product.status = 1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByCategoryId($id){
		$sql = "select * from ".self::$tablename." where category_id=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	/*
	public static function getProductServiceLike($p){
		$sql = "SELECT 'Producto' as tipo,id,barcode,name,description,total,inventary_min,category_id FROM product where (barcode like '%$p%' or name like '%$p%' or id like '%$p%') and status = 1 UNION ALL SELECT 'Servicio' as tipo,id,barcode,name,description,total,1 as inventary_min,category_id FROM services where (barcode like '%$p%' or name like '%$p%' or id like '%$p%') and status = 1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}*/

	public static function getLike($p){
		$sql = "select * from ".self::$tablename." where barcode like '%$p%' or name like '%$p%' or id like '%$p%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}
/*
	public static function getAllByUserId($user_id){
		$sql = "select * from ".self::$tablename." where user_id=$user_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}*/

}

?>