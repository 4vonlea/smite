<?php


use Dompdf\Dompdf;

class Transaction_m extends MY_Model
{
	protected $primaryKey = "id";
	protected $table = "transaction";

	public static $transaction_status = [
		'capture' => 'Transaction is accepted and ready for settlement.',
		'cancel' => 'Your Transaction is cancelled and will not be proceed to settlement.',
		'settlement' => "Your payment has successfully confirmed.",
		'deny' => "Your payment is denied by the bank or Online Payment Fraud Detection System.",
		"pending" => "Your payment has not been received. Please make it before the time limit elapse",
		"expired" => "Transfer due date has elapsed. Please re-choose event(s) and re-checkout",
		"waiting" => "Waiting costumer to choose event(s) and checkout.",
		self::STATUS_NEED_VERIFY => 'Your payment is waiting for verification from the admin'
	];

	const STATUS_FINISH = "settlement";
	const STATUS_WAITING = "waiting";
	const STATUS_PENDING = "pending";
	const STATUS_UNFINISH = "unfinish";
	const STATUS_EXPIRE = "expired";
	const STATUS_DENY = "deny";
	const STATUS_NEED_VERIFY = "need_verification";

	const CHANNEL_GL = "GUARANTEE LETTER";

	const GL_PAID_MESSAGE = "Guarantee letter Paid";

	const ADMIN_FEE_START = 5000;

	public function gridConfig($options = array())
	{
		return [
			'relationships' => [
				'member' => ['members', 'member.id = member_id', 'left']
			],
			'select' => ['invoice' => 't.id', 't_id' => 't.id', 'fullname' => 'COALESCE(fullname,t.member_id)', 'status_payment','channel', 't_updated_at' => 't.updated_at', 'm_id' => 'member.id'],
			//			'filter'=>['checkout'=>'1'],
			'sort' => ['t.updated_at', 'desc']
		];
	}

	public function countSettlement($member_id){
		return $this->find()->join("transaction_details dt","dt.transaction_id = transaction.id")
			->group_start()
			->where("transaction.status_payment",self::STATUS_FINISH)
			->or_where("channel",self::CHANNEL_GL)
			->group_end()
			->where("dt.member_id",$member_id)->count_all_results();
	}

	public function gridDataGl($params, $relationship = [])
	{
		$gridConfig = $this->gridConfig();
		$gridConfig['select'] = array_merge($gridConfig['select'],[
			'sponsor'=>"JSON_UNQUOTE(JSON_EXTRACT(midtrans_data,'$.sponsorName'))",//"midtrans_data->>'$.sponsorName'",
			'pay_plan_date'=>"JSON_UNQUOTE(JSON_EXTRACT(midtrans_data,'$.payPlanDate'))",//"midtrans_data->>'$.payPlanDate'",
			'filename'=>"JSON_UNQUOTE(JSON_EXTRACT(midtrans_data,'$.fileName'))",//"midtrans_data->>'$.fileName'",
			'receiptPayment'=>"JSON_UNQUOTE(JSON_EXTRACT(midtrans_data,'$.receiptPayment'))",//"midtrans_data->>'$.receiptPayment'",
			'status_gl'=>"IF(message_payment = '".self::GL_PAID_MESSAGE."','Paid','Unpaid')"
		]);
		$gridConfig['filter'] = ['channel'=>Transaction_m::CHANNEL_GL];
		$data = parent::gridData($params, $gridConfig);
		$result = $this->find()->select("COUNT(id) as total")
			->select("COALESCE(SUM(IF(message_payment = '".self::GL_PAID_MESSAGE."',1,0)),0) as paid")
			->select("COALESCE(SUM(IF(message_payment != '".self::GL_PAID_MESSAGE."',1,0)),0) as unpaid")
			->where("channel",self::CHANNEL_GL)
			->get()->row_array();
		$data['total_transaction'] = $result['total'];
		$data['paid_transaction'] = $result['paid'];
		$data['unpaid_transaction'] = $result['unpaid'];
		return $data;
	}

	public function gridData($params, $relationship = [])
	{
		$global_filter = (isset($params['global_filter'])?$params['global_filter']:null);
		if($global_filter){
			$relationship = (count($relationship) > 0 ? $relationship:$this->gridConfig());
			$result = $this->db->like("fullname",$global_filter)
				->or_like("email",$global_filter)
				->from("members")
				->select("transaction_id")
				->join("transaction_details","members.id = member_id")
				->get();
			foreach($result->result_array() as $row){
				$relationship['additional_search'][$row['transaction_id']] = 't.id';
			}
		}
		$data = parent::gridData($params, $relationship);
		$result = $this->find()->select("SUM(IF(status_payment = '" . self::STATUS_FINISH . "',1,0)) as finish")
			->select("SUM(IF(status_payment = 'capture' OR status_payment = 'pending',1,0)) as pending")
			->select("SUM(IF(status_payment = 'waiting',1,0)) as waiting")
			->select("SUM(IF(status_payment = 'need_verification',1,0)) as need_verify")
			->select("SUM(IF(status_payment = 'cancel' OR status_payment = 'deny' OR status_payment = 'expired',1,0)) as unfinish")
			->get()->row_array();
		$data['total_waiting'] = $result['waiting'];
		$data['total_finish'] = $result['finish'];
		$data['total_unfinish'] = $result['unfinish'];
		$data['total_pending'] = $result['pending'];
		$data['total_need_verify'] = $result['need_verify'];
		return $data;
	}

	public function midtransTransactionStatusDefinition($status)
	{
		$status = strtolower($status);
		return isset(self::$transaction_status[$status]) ? self::$transaction_status[$status] : "-";
	}

	public function generateInvoiceId()
	{
		$prefix = "INV-" . date("Ymd") . "-";
		$rs = $this->find()->like("id", $prefix, "after")->order_by("id", "DESC")->limit(1)->get();
		$row = $rs->row();
		$no = 1;
		if ($row) {
			$expl = explode("-", $row->id);
			$no = isset($expl[2]) ? $expl[2] + 1 : 1;
		}
		return $prefix . str_pad($no, 5, "0", STR_PAD_LEFT);
	}

	public function getTotalAmount($invoiceId)
	{
		return $this->db->select_sum("price")
			->from($this->table)->join("transaction_details", 'transaction_id = transaction.id')
			->where('transaction.id', $invoiceId)
			->get();
	}
	public function detailsWithEvent()
	{
		$rs = $this->db->select("t.*,e.id as event_id,e.name as event_name,e.theme, e.held_on,e.held_in,e.theme, m.fullname as member_name")
			->join("event_pricing ep", "ep.id = t.event_pricing_id", "left")
			->join("events e", "e.id = ep.event_id", "left")
			->join("members m", "m.id = t.member_id", "left")
			->where("transaction_id", $this->id)->get("transaction_details t");
		return $rs->result();
	}
	public function details()
	{
		return $this->hasMany('Transaction_detail_m', 'transaction_id');
	}

	public function member()
	{
		return $this->hasOne("Member_m", "id", "member_id");
	}

	/**
	 * getMember
	 *
	 * Mengambil data member
	 *
	 * @return void
	 */
	public function getMember($where = [])
	{
		return $this->db->from('`transaction` t')->join('`members` m', 'm.`id` = t.`member_id`')->where($where)->get();
	}

	/**
	 * getMemberGroup
	 *
	 * mengambil list member
	 *
	 * @return void
	 */
	public function getMemberGroup($id_invoice)
	{
		$rs = $this->db->select("m.*")
			->join('`transaction_details` td', 'td.`transaction_id` = t.`id`')
			->join('`members` m', 'm.`id` = td.`member_id`')
			->where('t.`id`', $id_invoice)
			->group_by('m.`id`')->get("{$this->table} t");
		return $rs->result_array();
	}

	/**
	 * @return Dompdf
	 */
	public function exportInvoice()
	{
		$domInvoice = new Dompdf();
		$domInvoice->setPaper('legal');
		$html = $this->load->view("template/invoice", [
			'transaction' => $this,
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
	public function exportPaymentProof()
	{
		require_once APPPATH . "third_party/phpqrcode/qrlib.php";
		$html = $this->load->view("template/official_payment_proof", [
			'transaction' => $this,
		], true);
		$dompdf = new Dompdf();
		$option = new \Dompdf\Options();
		$option->setIsRemoteEnabled(true);
		$dompdf->setOptions($option);
		$dompdf->setPaper('legal', 'potrait');
		$dompdf->loadHtml($html);
		$dompdf->render();
		return $dompdf;
	}

	public function findByDetail($member_id){
		$trIdList =  $this->db->select("t.id")
			->from("transaction t")
			->join("transaction_details td","t.id = td.transaction_id")
			->where("td.member_id",$member_id)
			->group_by("t.id")
			->get()->result();
		$trIdArray = [];
		foreach($trIdList as $row){
			$trIdArray[] = $row->id;
		}
		return $this->findAll(['id'=>$trIdArray]);
	}

	public function getNotFollowedEvent($member_id)
	{
		$rs = $this->db->query("SELECT e.name as event_name,ev.* FROM events e
			JOIN members m ON m.id = '$member_id'
			JOIN kategory_members km ON km.id = m.status
			JOIN event_pricing ev ON ev.event_id = e.id AND ev.`condition` = km.kategory
			WHERE ev.id NOT IN (
			SELECT td.event_pricing_id FROM transaction_details td
			JOIN `transaction` tr ON tr.id = td.transaction_id WHERE tr.member_id = '$member_id' AND tr.status_payment != '".self::STATUS_EXPIRE."'
		)");
		return $rs->result_array();
	}

	public function count_participant()
	{
		$result = $this->db->query("SELECT COUNT(DISTINCT m.id) AS j FROM transaction_details td
							JOIN transaction t ON t.id = td.transaction_id
							JOIN members m ON m.id = td.member_id
							JOIN event_pricing ep ON ep.id = td.event_pricing_id
							JOIN `events` e ON e.id = ep.event_id
							WHERE t.status_payment = 'settlement'")->row();
		return $result->j;
	}

	/**
	 * getTransactionGroup
	 *
	 * Mengambil data transaksi
	 *
	 * @return void
	 */
	public function getTransactionGroup($id)
	{
		$rs = $this->db->select("t.checkout, ts.*")
			->join("transaction_details ts", "t.id = ts.transaction_id")
			->where("t.id", $id)->get("{$this->table} t");
		return $rs->result();
	}

	public function toArray()
	{
		$data = parent::toArray();
		$data['paymentGatewayInfo'] = [
			'product'=>'',
			'productNumber'=>'',
			'expired'=>'',
		];
		if($data['channel'] == "ESPAY"){
			$paymentGatewayData = json_decode($data['midtrans_data'],true);
			if(is_array($paymentGatewayData)){
				$data['paymentGatewayInfo']['product'] = $paymentGatewayData['product_name'] ?? "";
				$data['paymentGatewayInfo']['productNumber'] = $paymentGatewayData['product_value'] ?? ""; 
				$data['paymentGatewayInfo']['expired'] = $paymentGatewayData['expired'] ?? "";
			}
		}
		return $data;
	}
}
