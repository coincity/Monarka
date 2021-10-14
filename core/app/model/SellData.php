<?php
date_default_timezone_set('America/La_Paz');
class SellData {
	public static $tablename = "sell";

	public function __construct(){
        $this->status = "1";
        $this->user_id = $_SESSION['user_id'];
		$this->created_at = "NOW()";
		$this->client_info = $_SERVER['HTTP_USER_AGENT'];
		$this->ncf = "";
		$this->payment_method = "";
		$this->note = "";
	}

	public function getPerson(){ return PersonData::getById($this->person_id);}
	public function getUser(){ return UserData::getById($this->user_id);}
	public function getP(){ return PData::getById($this->p_id);}
	public function getStockFrom(){ return StockData::getById($this->stock_from_id);}
	public function getStockTo(){ return StockData::getById($this->stock_to_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (person_id,p_id,subtotal,taxes,total,cash,iva,discount,ncf, payment_method, note,user_id,created_at) ";
		$sql .= "value ($this->person_id,$this->p_id,$this->subtotal,$this->taxes,$this->total,$this->cash,$this->iva,$this->discount,'$this->ncf','$this->payment_method','$this->note',$this->user_id,$this->created_at)";
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

	public function process_cotization(){
		$sql = "update ".self::$tablename." set stock_to_id=$this->stock_to_id,p_id=$this->p_id,d_id=$this->d_id,iva=$this->iva,total=$this->total,discount=$this->discount,cash=$this->cash,is_draft=0 where id=$this->id";
		Executor::doit($sql);
	}

	public function update_box(){
		$sql = "update ".self::$tablename." set box_id=$this->box_id where id=$this->id";
		Executor::doit($sql);

		$hsql = "INSERT INTO hsell(movimiento, history_date, user_id,client_info, id, ref_id, sell_from_id, person_id, operation_type_id, box_id, p_id, d_id, total, cash, iva, discount, is_draft, stock_to_id, stock_from_id, status, created_at)";
		$hsql .= " SELECT 'ACTUALIZAR',NOW(),".$_SESSION["user_id"].",\"$this->client_info\",id, ref_id, sell_from_id, person_id, operation_type_id, box_id, p_id, d_id, total, cash, iva, discount, is_draft, stock_to_id, stock_from_id, status, created_at FROM ".self::$tablename." where id = $this->id";
		Executor::doit($hsql);
	}

	public function update_d(){
		$sql = "update ".self::$tablename." set d_id=$this->d_id where id=$this->id";
		Executor::doit($sql);

		$hsql = "INSERT INTO hsell(movimiento, history_date, user_id,client_info, id, ref_id, sell_from_id, person_id, operation_type_id, box_id, p_id, d_id, total, cash, iva, discount, is_draft, stock_to_id, stock_from_id, status, created_at)";
		$hsql .= " SELECT 'ACTUALIZAR',NOW(),".$_SESSION["user_id"].",\"$this->client_info\",id, ref_id, sell_from_id, person_id, operation_type_id, box_id, p_id, d_id, total, cash, iva, discount, is_draft, stock_to_id, stock_from_id, status, created_at FROM ".self::$tablename." where id = $this->id";
		Executor::doit($hsql);
	}

	public function update_p(){
		$sql = "update ".self::$tablename." set p_id=$this->p_id where id=$this->id";
		Executor::doit($sql);

		$hsql = "INSERT INTO hsell(movimiento, history_date, user_id,client_info, id, ref_id, sell_from_id, person_id, operation_type_id, box_id, p_id, d_id, total, cash, iva, discount, is_draft, stock_to_id, stock_from_id, status, created_at)";
		$hsql .= " SELECT 'ACTUALIZAR',NOW(),".$_SESSION["user_id"].",\"$this->client_info\",id, ref_id, sell_from_id, person_id, operation_type_id, box_id, p_id, d_id, total, cash, iva, discount, is_draft, stock_to_id, stock_from_id, status, created_at FROM ".self::$tablename." where id = $this->id";
		Executor::doit($hsql);
	}

	public function update_payment(){
		$sql = "update ".self::$tablename." set p_id=$this->p_id and cash=$this->cash where id=$this->id";
		Executor::doit($sql);

		$hsql = "INSERT INTO hsell(movimiento, history_date, user_id,client_info, id, ref_id, sell_from_id, person_id, operation_type_id, box_id, p_id, d_id, total, cash, iva, discount, is_draft, stock_to_id, stock_from_id, status, created_at)";
		$hsql .= " SELECT 'ACTUALIZAR',NOW(),".$_SESSION["user_id"].",\"$this->client_info\",id, ref_id, sell_from_id, person_id, operation_type_id, box_id, p_id, d_id, total, cash, iva, discount, is_draft, stock_to_id, stock_from_id, status, created_at FROM ".self::$tablename." where id = $this->id";
		Executor::doit($hsql);
	}

	public static function getById($id){
        $sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}

	public function cancel(){
		$sql = "update ".self::$tablename." set d_id=3,p_id=3 where id=$this->id";
		Executor::doit($sql);
	}

	public static function getAll(){
		//$sql = "select id,ref_id,person_id,p_id,total,cash,discount,status,user_id,created_at,IFNULL(pagado, 0) as pagado, pendiente,name,lastname";
        $sql = "select *from ( SELECT * FROM( SELECT s.id,0 as transaction_id,s.person_id,person.name,person.lastname,s.user_id,s.p_id,'CONTADO' as tipo,s.total as facturado,f.total as pagado ,ifnull(p.total,0) as pendiente,ifnull(-1*(select sum(val) from payment WHERE id <= pa.Id and sell_id <= s.id and person_id = s.person_id),0) as totalpendiente,0 as saldada,s.cash,s.iva,s.discount,s.status,s.created_at FROM	sell s
		            INNER JOIN (select SUM(val) as total,sell_id from payment_summary group by sell_id) f on f.sell_id = s.id LEFT JOIN (select SUM(val) as total,sell_id from payment group by sell_id) p on p.sell_id = s.id INNER JOIN person on s.person_id = person.id LEFT JOIN payment pa on s.id = pa.sell_id WHERE p_id = 1 ORDER BY s.created_at) A
	                UNION ALL SELECT * FROM( SELECT s.id,p.id as transaction_id,p.person_id,person.name,person.lastname,p.user_id,s.p_id,CASE WHEN p.payment_type_id = 1 then 'CREDITO'	WHEN p.payment_type_id = 2 then 'ABONO'	END as tipo,s.total as facturado,CASE WHEN p.payment_type_id = 1 then 0
				    WHEN p.payment_type_id = 2 then p.val END as pagado,CASE WHEN (select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) = 0 THEN 0 ELSE -1*(select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) END as pendiente,ifnull(-1*(select sum(val) from payment WHERE id <= p.Id and sell_id <= s.id and person_id = s.person_id),0) as totalpendiente,p.saldada,s.cash,s.iva,s.discount,s.status,
                    p.created_at FROM sell s INNER JOIN payment p on s.id = p.sell_id INNER JOIN person on s.person_id = person.id WHERE p_id = 2 ORDER BY p.created_at) B
	                UNION ALL SELECT * FROM( SELECT	s.id,p.id as transaction_id,p.person_id,person.name,person.lastname,p.user_id,s.p_id,'ABONO' as tipo,s.total as facturado,p.val as pagado ,CASE WHEN (select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) = 0 THEN 0
				    ELSE -1*(select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) END as pendiente,ifnull(-1*(select sum(val) from payment WHERE id <= p.Id and sell_id <= s.id and person_id = s.person_id),0) as totalpendiente,p.saldada,s.cash,s.iva,s.discount,s.status,p.created_at FROM sell s INNER JOIN payment p on s.id = p.sell_id INNER JOIN person on s.person_id = person.id WHERE p_id = 3 and payment_type_id = 2 ORDER BY p.created_at) C
                ) a order by a.created_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	
	public static function getAllSellByDate($start, $end){
		//sql = "select total from ".self::$tablename." WHERE date(created_at) BETWEEN $start AND $end order by a.created_at";
		//$sql = "select id,ref_id,person_id,p_id,total,cash,discount,status,user_id,created_at,IFNULL(pagado, 0) as pagado, pendiente,name,lastname";
        $sql = "select *from ( SELECT * FROM( SELECT s.id,0 as transaction_id,s.person_id,person.name,person.lastname,s.user_id,s.p_id,'CONTADO' as tipo,s.total as facturado,f.total as pagado ,ifnull(p.total,0) as pendiente,ifnull(-1*(select sum(val) from payment WHERE id <= pa.Id and sell_id <= s.id and person_id = s.person_id),0) as totalpendiente,0 as saldada,s.cash,s.iva,s.discount,s.status,s.created_at FROM	sell s
		            INNER JOIN (select SUM(val) as total,sell_id from payment_summary group by sell_id) f on f.sell_id = s.id LEFT JOIN (select SUM(val) as total,sell_id from payment group by sell_id) p on p.sell_id = s.id INNER JOIN person on s.person_id = person.id LEFT JOIN payment pa on s.id = pa.sell_id WHERE p_id = 1 ORDER BY s.created_at) A
	                UNION ALL SELECT * FROM( SELECT s.id,p.id as transaction_id,p.person_id,person.name,person.lastname,p.user_id,s.p_id,CASE WHEN p.payment_type_id = 1 then 'CREDITO'	WHEN p.payment_type_id = 2 then 'ABONO'	END as tipo,s.total as facturado,CASE WHEN p.payment_type_id = 1 then 0
				    WHEN p.payment_type_id = 2 then p.val END as pagado,CASE WHEN (select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) = 0 THEN 0 ELSE -1*(select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) END as pendiente,ifnull(-1*(select sum(val) from payment WHERE id <= p.Id and sell_id <= s.id and person_id = s.person_id),0) as totalpendiente,p.saldada,s.cash,s.iva,s.discount,s.status,
                    p.created_at FROM sell s INNER JOIN payment p on s.id = p.sell_id INNER JOIN person on s.person_id = person.id WHERE p_id = 2 ORDER BY p.created_at) B
	                UNION ALL SELECT * FROM( SELECT	s.id,p.id as transaction_id,p.person_id,person.name,person.lastname,p.user_id,s.p_id,'ABONO' as tipo,s.total as facturado,p.val as pagado ,CASE WHEN (select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) = 0 THEN 0
				    ELSE -1*(select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) END as pendiente,ifnull(-1*(select sum(val) from payment WHERE id <= p.Id and sell_id <= s.id and person_id = s.person_id),0) as totalpendiente,p.saldada,s.cash,s.iva,s.discount,s.status,p.created_at FROM sell s INNER JOIN payment p on s.id = p.sell_id INNER JOIN person on s.person_id = person.id WHERE p_id = 3 and payment_type_id = 2 ORDER BY p.created_at) C
                ) a WHERE date(created_at) >= \"$start\" and date(created_at) <= \"$end\" order by a.created_at";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

    public static function getSellsDetails($sell_id,$transaction_id){
		$sql = "select *from ( SELECT * FROM( SELECT s.id,0 as transaction_id,s.person_id,person.name,person.lastname,s.user_id,s.p_id,'CONTADO' as tipo,s.total as facturado,f.total as pagado ,ifnull(p.total,0) as pendiente,ifnull(-1*(select sum(val) from payment WHERE id <= pa.Id and sell_id <= s.id and person_id = s.person_id),0) as totalpendiente,0 as saldada,s.cash,s.iva,s.discount,s.status,s.created_at FROM	sell s
		            INNER JOIN (select SUM(val) as total,sell_id from payment_summary group by sell_id) f on f.sell_id = s.id LEFT JOIN (select SUM(val) as total,sell_id from payment group by sell_id) p on p.sell_id = s.id INNER JOIN person on s.person_id = person.id LEFT JOIN payment pa on s.id = pa.sell_id WHERE p_id = 1 ORDER BY s.created_at) A
	                UNION ALL SELECT * FROM( SELECT s.id,p.id as transaction_id,p.person_id,person.name,person.lastname,p.user_id,s.p_id,CASE WHEN p.payment_type_id = 1 then 'CREDITO'	WHEN p.payment_type_id = 2 then 'ABONO'	END as tipo,s.total as facturado,CASE WHEN p.payment_type_id = 1 then 0
				    WHEN p.payment_type_id = 2 then p.val END as pagado,CASE WHEN (select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) = 0 THEN 0 ELSE -1*(select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) END as pendiente,ifnull(-1*(select sum(val) from payment WHERE id <= p.Id and sell_id <= s.id and person_id = s.person_id),0) as totalpendiente,p.saldada,s.cash,s.iva,s.discount,s.status,
                    p.created_at FROM sell s INNER JOIN payment p on s.id = p.sell_id INNER JOIN person on s.person_id = person.id WHERE p_id = 2 ORDER BY p.created_at) B
	                UNION ALL SELECT * FROM( SELECT	s.id,p.id as transaction_id,p.person_id,person.name,person.lastname,p.user_id,s.p_id,'ABONO' as tipo,s.total as facturado,p.val as pagado ,CASE WHEN (select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) = 0 THEN 0
				    ELSE -1*(select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) END as pendiente,ifnull(-1*(select sum(val) from payment WHERE id <= p.Id and sell_id <= s.id and person_id = s.person_id),0) as totalpendiente,p.saldada,s.cash,s.iva,s.discount,s.status,p.created_at FROM sell s INNER JOIN payment p on s.id = p.sell_id INNER JOIN person on s.person_id = person.id WHERE p_id = 3 and payment_type_id = 2 ORDER BY p.created_at) C
                ) a where a.id = $sell_id and a.transaction_id = $transaction_id order by a.created_at";
		$query = Executor::doit($sql);
		return Model::one($query[0],new SellData());
	}

    public static function getPaymentAll(){
		//$sql = "select ".self::$tablename.".*,person.name,person.lastname from ".self::$tablename." inner join person on ".self::$tablename.".person_id = person.id";
        $sql = "select id,ref_id,person_id,p_id,total,cash,discount,status,user_id,created_at,IFNULL(pagado, 0) as pagado, pendiente,name,lastname from (
            select sell.*,
            CASE p_id
               when 1 then b.val
               when 2 then c.val
               when 3 then c.val
            END as pagado
            ,
            CASE p_id
               when 1 then 0
               else (d.val * -1)
            END as pendiente
            ,person.name,person.lastname from sell inner join person on sell.person_id = person.id
            LEFT JOIN (SELECT sell_id,sum(val) as val from payment_summary group by sell_id) b on sell.id = b.sell_id
            LEFT JOIN (SELECT sell_id,sum(val) as val from payment WHERE payment_type_id = 2 group by sell_id) c on sell.id = c.sell_id
            LEFT JOIN (SELECT sell_id,sum(val) as val from payment group by sell_id) d on sell.id = d.sell_id
            ) a  WHERE pendiente > 0";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCredits(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getCreditsByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function CountByCategory($id){
		$sql = "select sum(total) as s from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ServiceData());
	}

	public static function getCreditsByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=4 and is_draft=0 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=1 and d_id=1 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToDeliver(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToDeliverByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsToDeliverByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToDeliverByClient($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and d_id=2 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToCob(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToCobByUserId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 and user_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}
	public static function getSellsToCobByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsToCobByClientId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and p_id=2 and is_draft=0 and person_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSellsUnBoxed(){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id is NULL and p_id=1 and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getByBoxId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=2 and box_id=$id and is_draft=0 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getRes(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and d_id in (1,2,3) order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=1 and d_id=1 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResToPay(){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=2  order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getResToPayByStockId($id){
		$sql = "select * from ".self::$tablename." where operation_type_id=1 and p_id=2 and stock_to_id=$id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getSQL($sql){
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllBySQL($sqlextra){
		$sql = "select * from ".self::$tablename." $sqlextra";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllByDateOp($start,$end,$op){
        $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and is_draft=0 and p_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllByOp($start,$end,$op){
        $sql = "select * from ".self::$tablename." where operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllByDateOpByUserId($user,$start,$end,$op){
        $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllByOpByUserId($user,$start,$end,$op){
        $sql = "select * from ".self::$tablename." where operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

    public static function getGroupByDateOp($start,$end,$op){
        $sql = "select id,sum(total) as tot,discount,sum(total-discount) as t,count(*) as c from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and operation_type_id=$op";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());
	}

	public static function getAllByDateBCOp($clientid,$start,$end,$op){
        $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and person_id=$clientid  and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

	public static function getAllByBCOp($clientid,$start,$end,$op){
        $sql = "select * from ".self::$tablename." where person_id=$clientid  and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

	public static function getAllByDateBCOpByUserId($user,$clientid,$start,$end,$op){
        $sql = "select * from ".self::$tablename." where date(created_at) >= \"$start\" and date(created_at) <= \"$end\" and person_id=$clientid  and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}

    public static function getAllByBCOpByUserId($user,$clientid,$start,$end,$op){
        $sql = "select * from ".self::$tablename." where person_id=$clientid  and operation_type_id=$op and is_draft=0 and p_id=1 and d_id=1 and user_id=$user order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new SellData());

	}
}

?>