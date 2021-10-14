<?php
class PersonData {
	public static $tablename = "person";

	public function __construct() {
        $this->status = "1";
		$this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
		$this->client_info = $_SERVER['HTTP_USER_AGENT'];
		$this->client_type_id = "NULL";
	}

	public function add_employee() {
		$sql = "insert into ".self::$tablename." (image,no, name, lastname, address, email, phone,cell,gender,birthdate,hiredate,marital_status,tipo,status,user_id,created_at) ";
		$sql .= "value (\"$this->image\",\"$this->no\",\"$this->name\",\"$this->lastname\",\"$this->address\",\"$this->email\",\"$this->phone\",\"$this->cell\",\"$this->gender\",\"$this->birthdate\",\"$this->hiredate\",\"$this->marital_status\",1,1,$this->user_id,NOW())";
		return  Executor::doit($sql);
	}

	public function add_client() {
		$sql = "insert into ".self::$tablename." (image,no, name, lastname, address, email, phone,cell,tipo,status,client_type_id,user_id,created_at) ";
		$sql .= "value (\"$this->image\",\"$this->no\",\"$this->name\",\"$this->lastname\",\"$this->address\",\"$this->email\",\"$this->phone\",\"$this->cell\",3,1, $this->client_type_id,$this->user_id,NOW())";
		return  Executor::doit($sql);
	}

	public function add_provider() {
		$sql = "insert into ".self::$tablename." (no,name,lastname,company,address,email,phone,cell,tipo,status,user_id,created_at) ";
		$sql .= "value (\"$this->no\",\"$this->name\",\"$this->lastname\",\"$this->company\",\"$this->address\",\"$this->email\",\"$this->phone\",\"$this->cell\",2,1,$this->user_id,NOW())";
		Executor::doit($sql);
	}

    public function add_comment($person_id,$comentarios) {
		$sql = "INSERT INTO observations (person_id,observation,user_id,created_at) ";
		$sql .= "value ($person_id,\"$comentarios\",\"$this->user_id\",NOW())";
		return Executor::doit($sql);
	}

	public function update_employee(){
		$sql = "update ".self::$tablename." set no=\"$this->no\",name=\"$this->name\",email=\"$this->email\",address=\"$this->address\",lastname=\"$this->lastname\",phone=\"$this->phone\",cell=\"$this->cell\",gender=\"$this->gender\",birthdate=\"$this->birthdate\",marital_status=\"$this->marital_status\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update_client(){
		$sql = "update ".self::$tablename." set no=\"$this->no\",name=\"$this->name\",lastname=\"$this->lastname\",address=\"$this->address\",phone=\"$this->phone\",cell=\"$this->cell\",email=\"$this->email\",user_id=$this->user_id, client_type_id=$this->client_type_id where id=$this->id";
		return Executor::doit($sql);
	}

	public function update_provider(){
		$sql = "update ".self::$tablename." set no=\"$this->no\",name=\"$this->name\",company=\"$this->company\",email=\"$this->email\",address=\"$this->address\",lastname=\"$this->lastname\",phone=\"$this->phone\",cell=\"$this->cell\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update_contact(){
		$sql = "update ".self::$tablename." set name=\"$this->name\",email1=\"$this->email1\",address1=\"$this->address1\",lastname=\"$this->lastname\",phone1=\"$this->phone1\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

	public function update_passwd(){
		$sql = "update ".self::$tablename." set password=\"$this->password\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}


	public function update_image(){
		$sql = "update ".self::$tablename." set image=\"$this->image\",user_id=$this->user_id where id=$this->id";
		Executor::doit($sql);
	}

    public function update_comment($comentarios){
		$sql = "update observations set observation=\"$comentarios\" where person_id=$this->id";
		return Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PersonData());
	}

	public static function getByNo($id){
		$sql = "select * from ".self::$tablename." where no=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());
	}

    public static function getByNoPacient($id){
		$sql = "select * from ".self::$tablename." where no=$id and tipo = 1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());
	}

	public static function getByNoById($id,$person_id){
		$sql = "select * from ".self::$tablename." where no=$id and id <> $person_id and tipo = 1";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());
	}

	public static function getAll(){
		$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo, sta.description AS status_dsc FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo LEFT JOIN status AS sta ON sta.id = per.status where kind = 1 ORDER BY per.name, per.lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());

	}

    public static function getComments($id){
		$sql = "select * from observations where person_id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new PersonData());
	}

	public static function getClients(){
		$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo, sta.description AS status_dsc FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo LEFT JOIN status AS sta ON sta.id = per.status WHERE per.tipo = 3 ORDER BY per.name, per.lastname";
		//$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo WHERE per.kind=1 ORDER BY per.name,per.lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

    public static function getClientsActive(){
		$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo, sta.description AS status_dsc FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo LEFT JOIN status AS sta ON sta.id = per.status WHERE per.tipo = 3 AND per.status = 1 ORDER BY per.name, per.lastname";
		//$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo WHERE per.kind=1 ORDER BY per.name,per.lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

    public static function getProvidersActive(){
		$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo, sta.description AS status_dsc FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo LEFT JOIN status AS sta ON sta.id = per.status WHERE per.tipo = 2 AND per.status = 1 ORDER BY per.name, per.lastname";
		//$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo WHERE per.kind=1 ORDER BY per.name,per.lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getBirthDay(){
		$sql = "SELECT * FROM ".self::$tablename." WHERE tipo in(1,2,3,4,5,6,7,8,9,10,11) AND DATE_FORMAT(fecha_nacimiento, '%m-%d') = DATE_FORMAT(NOW(), '%m-%d') ORDER BY name, lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getPersonByType($typePerson){
		$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo, sta.description AS status_dsc FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo LEFT JOIN status AS sta ON sta.id = per.status WHERE per.kind = 1 AND pti.description LIKE '%$typePerson%' AND status = 1 ORDER BY per.name, per.lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getPersonByTypeAll($typePerson){
		$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo, sta.description AS status_dsc FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo LEFT JOIN status AS sta ON sta.id = per.status WHERE per.kind = 1 AND pti.description LIKE '%$typePerson%' ORDER BY per.name, per.lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getPersonByAll($typePerson){
		$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo, sta.description AS status_dsc,sta.color,sta.label_description FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo LEFT JOIN status AS sta ON sta.id = per.status WHERE pti.description LIKE '%$typePerson%' ORDER BY per.name, per.lastname";
		$query = Executor::doit($sql);

		return Model::many($query[0],new PersonData());
	}

	public static function getAudit($typePerson){
		$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo, sta.description AS status_dsc FROM hperson AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo LEFT JOIN status AS sta ON sta.id = per.status WHERE per.kind = 1 AND pti.description LIKE '%$typePerson%' ORDER BY per.name, per.lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getSpecialist(){
		$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo WHERE per.kind=1 AND tipo in(2,3,4,5,6,7,8,9,10,11) ORDER BY per.name,per.lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getSpecialistActive(){
		$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo WHERE per.kind=1 AND tipo in(2,3,4,5) and per.status = 1 ORDER BY per.name,per.lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getSpecialistByService($id){
		$sql = "SELECT per.*, IFNULL(pti.description,'0') AS nombre_tipo,ss.service_id FROM ".self::$tablename." AS per LEFT JOIN person_type AS pti ON pti.id = per.tipo LEFT JOIN service_specialist ss ON per.id = ss.specialist_id WHERE per.kind=1 AND tipo in(2,3,4,5) and ss.service_id =$id and per.status = 1 ORDER BY per.name,per.lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getClientsWithCredit(){
		$sql = "select * from ".self::$tablename." where kind=1 and has_credit=1 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getContacts(){
		$sql = "select * from ".self::$tablename." where kind=3 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getProviders(){
		$sql = "select * from ".self::$tablename." where tipo=2";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getActiveProviders(){
		$sql = "select * from ".self::$tablename." where kind=2 and status=1 order by name,lastname";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where name like '%$q%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new PersonData());
	}

}

?>