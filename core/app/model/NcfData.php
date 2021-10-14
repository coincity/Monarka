<?php
class NcfData {
	public static $tablename = "control_ncf";
	public function __construct(){
        $this->status = "1";
		$this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
        $this->client_info = $_SERVER['HTTP_USER_AGENT'];
	}

	public static function getAll(){
		$sql = "select a.*,nt.description as tipodocumento,ct.description as tipocliente from ".self::$tablename." a inner join ncf_type nt on a.tipodoc = nt.id inner join client_type ct on a.tipo = ct.id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new NcfData());
	}

    public static function getById($id){
		$sql = "select *from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new NcfData());
	}

	public static function getRepeated($tipodoc,$tipo,$fecinivig,$fecfinvig){
		$sql = "select *from ".self::$tablename." where tipodoc=\"$tipodoc\" and tipo=\"$tipo\" and fecinivig=\"$fecinivig\" and fecfinvig=\"$fecfinvig\"";
		$query = Executor::doit($sql);
		return Model::many($query[0],new NcfData());
	}

	 public static function getRepeatedUpdate($id,$tipodoc,$tipo,$fecinivig,$fecfinvig){
		$sql = "select *from ".self::$tablename." where id!=$id and tipodoc=\"$tipodoc\" and tipo=\"$tipo\" and fecinivig=\"$fecinivig\" and fecfinvig=\"$fecfinvig\"";
		$query = Executor::doit($sql);
		return Model::many($query[0],new NcfData());
	}

     public static function getNCF($tipodoc,$tipo,$date){
         $sql = "select a.*,nt.code from ".self::$tablename." a inner join ncf_type nt on a.tipodoc = nt.id where tipodoc=$tipodoc and tipo=$tipo and \"$date\" >= fecinivig and \"$date\" <= fecfinvig and a.status=1";
         $query = Executor::doit($sql);
         return Model::one($query[0],new NcfData());
     }

    public function add(){
		$sql = "insert into ".self::$tablename." (tipodoc,tipo,fecinivig,fecfinvig,secuenciaini,secuenciafin,secuenciaactual,status,user_id,created_at) ";
		$sql .= "values (\"$this->tipodoc\",\"$this->tipo\",\"$this->fecinivig\",\"$this->fecfinvig\",$this->secuenciaini,$this->secuenciafin,$this->secuenciaini,1,$this->user_id,NOW())";
		Executor::doit($sql);
	}

    public function update(){
		$sql = "update ".self::$tablename." set tipodoc=\"$this->tipodoc\" ,tipo=\"$this->tipo\" ,fecinivig = \"$this->fecinivig\" ,fecfinvig =\"$this->fecfinvig\" ,secuenciaini=$this->secuenciaini,secuenciafin=$this->secuenciafin where id=$this->id ";
		Executor::doit($sql);
	}

    public function updateSequence(){
		$sql = "update ".self::$tablename." set secuenciaactual=$this->secuenciaactual where id=$this->id ";
		Executor::doit($sql);
	}

}

?>