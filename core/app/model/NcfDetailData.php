<?php
class NcfDetailData {
	public static $tablename = "ncf_detail";

	public function __construct(){
        $this->status = "1";
		$this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}
    /*
	public static function getAll(){
		$sql = "select a.*,nt.description as tipodocumento,ct.description as tipocliente from ".self::$tablename." a inner join ncf_type nt on a.tipodoc = nt.id inner join client_type ct on a.tipo = ct.id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new NcfDetailData());
	}

    public static function getById($id){
		$sql = "select *from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new NcfDetailData());
	}*/

    public function add(){
		$sql = "insert into ".self::$tablename." (tipodoc,tipo,ncf,sell_id,status,user_id,created_at) ";
		$sql .= "values (\"$this->tipodoc\",\"$this->tipo\",\"$this->ncf\",$this->sell_id,1,$this->user_id,NOW())";
		Executor::doit($sql);
	}

    public static function getSaleNCF($sell_id){
		$sql = "select *from ".self::$tablename." where sell_id=$sell_id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new NcfDetailData());
	}
}

?>