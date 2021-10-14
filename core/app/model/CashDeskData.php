<?php
class CashDeskData {
	public static $tablename = "cashdesk";

	public function __construct(){
        $this->id = 0;
		$this->user_id = 0;
		$this->opening_time = "NOW()";
		$this->close_time = "NULL";
		$this->start_amount = "0.00";
        $this->end_amount = "0.00";
        $this->transactions = [];
        $this->user = [];
        $this->note = "";
        $this->unbalanced = "";
	}

	public function add(){
		$sql = "insert into ".self::$tablename."(user_id,opening_time,close_time,start_amount,end_amount, unbalanced, note) ";
		$sql .= "value ($this->user_id,$this->opening_time,$this->close_time,'$this->start_amount','$this->end_amount','$this->unbalanced','$this->note')";
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

	public function close(){
        self::fill($this);
        $sql = "update ".self::$tablename." set close_time=NOW(), end_amount=$this->end_amount where id=$this->id";
		Executor::doit($sql);
	}

    public static function fill($cashDesk) {
        if($cashDesk == null) {
            return null;
        }
        $sql = "select *from ( SELECT * FROM( SELECT s.id,0 as transaction_id,s.person_id,person.name,person.lastname,s.user_id,s.p_id,'CONTADO' as tipo,s.total as facturado,f.total as pagado ,ifnull(p.total,0) as pendiente,ifnull(-1*(select sum(val) from payment WHERE id <= pa.Id and sell_id <= s.id and person_id = s.person_id),0) as totalpendiente,0 as saldada,s.cash,s.iva,s.discount,s.status,s.created_at FROM	sell s
        INNER JOIN (select SUM(val) as total,sell_id from payment_summary group by sell_id) f on f.sell_id = s.id LEFT JOIN (select SUM(val) as total,sell_id from payment group by sell_id) p on p.sell_id = s.id INNER JOIN person on s.person_id = person.id LEFT JOIN payment pa on s.id = pa.sell_id WHERE p_id = 1 ORDER BY s.created_at) A
        UNION ALL SELECT * FROM( SELECT s.id,p.id as transaction_id,p.person_id,person.name,person.lastname,p.user_id,s.p_id,CASE WHEN p.payment_type_id = 1 then 'CREDITO'	WHEN p.payment_type_id = 2 then 'ABONO'	END as tipo,s.total as facturado,CASE WHEN p.payment_type_id = 1 then 0
        WHEN p.payment_type_id = 2 then p.val END as pagado,CASE WHEN (select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) = 0 THEN 0 ELSE -1*(select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) END as pendiente,ifnull(-1*(select sum(val) from payment WHERE id <= p.Id and sell_id <= s.id and person_id = s.person_id),0) as totalpendiente,p.saldada,s.cash,s.iva,s.discount,s.status,
        p.created_at FROM sell s INNER JOIN payment p on s.id = p.sell_id INNER JOIN person on s.person_id = person.id WHERE p_id = 2 ORDER BY p.created_at) B
        UNION ALL SELECT * FROM( SELECT	s.id,p.id as transaction_id,p.person_id,person.name,person.lastname,p.user_id,s.p_id,'ABONO' as tipo,s.total as facturado,p.val as pagado ,CASE WHEN (select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) = 0 THEN 0
        ELSE -1*(select sum(val) from payment WHERE id <= p.Id and sell_id = s.id) END as pendiente,ifnull(-1*(select sum(val) from payment WHERE id <= p.Id and sell_id <= s.id and person_id = s.person_id),0) as totalpendiente,p.saldada,s.cash,s.iva,s.discount,s.status,p.created_at FROM sell s INNER JOIN payment p on s.id = p.sell_id INNER JOIN person on s.person_id = person.id WHERE p_id = 3 and payment_type_id = 2 ORDER BY p.created_at) C
    ) a where a.user_id=$cashDesk->user_id and a.created_at > COALESCE(". ($cashDesk->opening_time == "" ? "NULL" : ('\''.$cashDesk->opening_time).'\'') .", NOW()) and a.created_at < COALESCE(". ($cashDesk->close_time == "" ? "NULL" : ('\''.$cashDesk->close_time).'\'') .", NOW()) order by a.created_at";
        $query = Executor::doit($sql);
        $cashDesk->transactions = Model::many($query[0],new SellData());
        $cashDesk->user=UserData::getById($cashDesk->user_id);
        $cashDesk->end_amount = Model::one(Executor::doit("SELECT COALESCE(SUM(total),0) total FROM sell as s WHERE s.user_id=$cashDesk->user_id AND s.created_at BETWEEN '$cashDesk->opening_time' AND COALESCE(". ($cashDesk->close_time == "" ? "NULL" : ('\''.$cashDesk->close_time).'\'') .", NOW())")[0])["total"];
        return $cashDesk;
    }

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
        $query = Executor::doit($sql);
        $cashDesk = Model::one($query[0],new CashDeskData());
        CashDeskData::fill($cashDesk);
        
		return $cashDesk;
	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename;
        $query = Executor::doit($sql);
        
        $cashDesks = Model::many($query[0],new CashDeskData());

        foreach ($cashDesks as $key => $cashDesk) {
            CashDeskData::fill($cashDesk);
        }
		return $cashDesks;
    }
    
    public static function getOpenCashDeskByUserId($user_id) {
        $sql = "select * from ".self::$tablename." where user_id=$user_id AND close_time IS NULL";
		$query = Executor::doit($sql);
		return CashDeskData::fill(Model::one($query[0],new CashDeskData()));
    }

    public static function openUserCashDesk($user_id, $start_amount) {
        $alreadyOpenedCashDesk = self::getOpenCashDeskByUserId($user_id);
        if($alreadyOpenedCashDesk != null) {
            return $alreadyOpenedCashDesk;
        } 
        $cashDesk = new CashDeskData();
        $cashDesk->user_id = $user_id;
        $cashDesk->start_amount = $start_amount;
        $cashDesk->add();
        return self::getOpenCashDeskByUserId($user_id);
    }
}

?>