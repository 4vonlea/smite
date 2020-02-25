<?php


use Dompdf\Dompdf;

class Transaction_m extends MY_Model
{
	protected $primaryKey = "id";
	protected $table = "transaction";

	public static $transaction_status = [
		'capture'=>'Transaction is accepted and ready to for settlement.',
		'cancel'=>'Transaction is cancelled and will not proceed to settlement.',
		'settlement'=>"Funds from the transaction has moved from customer to merchant's account.",
		'deny'=>"Transaction is denied by the bank or Midtrans Fraud Detection System.",
		"pending"=>"payment transaction has not been processed and is waiting to be completed.",
		"expired"=>"Transaction has not been completed by the expiry date.",
		self::STATUS_NEED_VERIFY =>'Payment waiting verification from admin'
	];

	const STATUS_FINISH = "settlement";
	const STATUS_WAITING = "waiting";
	const STATUS_PENDING = "pending";
	const STATUS_UNFINISH = "unfinish";
	const STATUS_EXPIRE = "expired";
	const STATUS_DENY = "deny";
	const STATUS_NEED_VERIFY = "need_verification";

	public function gridConfig($options = array())
	{
		return [
			'relationships'=>[
				'member'=>['members','member.id = member_id']
			],
			'select'=>['invoice'=>'t.id','t_id'=>'t.id','fullname','status_payment','t_updated_at'=>'t.updated_at'],
//			'filter'=>['checkout'=>'1'],
		];
	}

	public function gridData($params, $relationship = [])
	{
		$data = parent::gridData($params, $relationship);
		$result = $this->find()->select("SUM(IF(status_payment = '".self::STATUS_FINISH."',1,0)) as finish")
		->select("SUM(IF(status_payment = 'capture' OR status_payment = 'pending' OR status_payment = 'waiting',1,0)) as pending")
		->select("SUM(IF(status_payment = 'waiting',1,0)) as waiting")
		->select("SUM(IF(status_payment = 'cancel' OR status_payment = 'deny' OR status_payment = 'expire',1,0)) as unfinish")
			->get()->row_array();
		$data['total_waiting'] = $result['waiting'];
		$data['total_finish'] = $result['finish'];
		$data['total_unfinish'] = $result['unfinish'];
		$data['total_pending'] = $result['pending'];
		return $data;
	}

	public function midtransTransactionStatusDefinition($status){
		$status = strtolower($status);
		return isset(self::$transaction_status[$status]) ?self::$transaction_status[$status]:"-";
	}

	public function generateInvoiceId(){
		$prefix = "INV-".date("Ymd")."-";
		$rs = $this->find()->like("id",$prefix,"after")->order_by("id","DESC")->limit(1)->get();
		$row = $rs->row();
		$no = 1;
		if($row){
			$expl = explode("-",$row->id);
			$no = isset($expl[2])?$expl[2]+1:1;
		}
		return $prefix.str_pad($no,5,"0",STR_PAD_LEFT);
	}
	public function detailsWithEvent(){
		$rs = $this->db->select("t.*,e.id as event_id,e.name as event_name,e.theme, e.held_on,e.held_in,e.theme")
				->join("event_pricing ep","ep.id = t.event_pricing_id","left")
				->join("events e","e.id = ep.event_id","left")
				->where("transaction_id",$this->id)->get("transaction_details t");
		return $rs->result();

	}
	public function details()
	{
		return $this->hasMany('Transaction_detail_m', 'transaction_id');
	}

	public function member(){
		return $this->hasOne("Member_m","id","member_id");
	}

	/**
	 * @return Dompdf
	 */
	public function exportInvoice(){
		$domInvoice = new Dompdf();
		$domInvoice->setPaper('legal');
		$html = $this->load->view("template/invoice",[
			'transaction'=>$this,
		],true);

		$option = new \Dompdf\Options();
		$option->setIsRemoteEnabled(true);
		$domInvoice->setOptions($option);

		$domInvoice->loadHtml($html);
		$domInvoice->render();
		return $domInvoice;
	}

	/**
	 * @return Dompdf
	 */
	public function exportPaymentProof(){
		require_once APPPATH . "third_party/phpqrcode/qrlib.php";
		$html = $this->load->view("template/official_payment_proof",[
			'transaction'=>$this,
		],true);
		$dompdf = new Dompdf();
		$option = new \Dompdf\Options();
		$option->setIsRemoteEnabled(true);
		$dompdf->setOptions($option);
		$dompdf->setPaper('legal', 'potrait');
		$dompdf->loadHtml($html);
		$dompdf->render();
		return $dompdf;
	}
}
