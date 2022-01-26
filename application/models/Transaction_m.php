<?php


use Dompdf\Dompdf;

class Transaction_m extends MY_Model
{
	protected $primaryKey = "id";
	protected $table = "transaction";

	public static $transaction_status = [
		'capture' => 'Transaction is accepted and ready to for settlement.',
		'cancel' => 'Transaction is cancelled and will not proceed to settlement.',
		'settlement' => "Payment has been successfully confirmed.",
		'deny' => "Transaction is denied by the bank or Midtrans Fraud Detection System.",
		"pending" => "payment transaction has not been processed and is waiting to be completed.",
		"expired" => "Transaction has not been completed by the expiry date.",
		"waiting" => "Waiting costumer to checkout process payment.",
		self::STATUS_NEED_VERIFY => 'Payment waiting verification from admin'
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
			'relationships' => [
				'member' => ['members', 'member.id = member_id', 'left']
			],
			'select' => ['invoice' => 't.id', 't_id' => 't.id', 'fullname' => 'COALESCE(fullname,t.member_id)', 'status_payment', 't_updated_at' => 't.updated_at', 'm_id' => 'member.id'],
			//			'filter'=>['checkout'=>'1'],
			'sort' => ['t.updated_at', 'desc']
		];
	}

	public function gridData($params, $relationship = [])
	{
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
		$rs = $this->db->select("t.*,e.id as event_id,e.name as event_name,e.theme, e.held_on,e.held_in,e.theme")
			->join("event_pricing ep", "ep.id = t.event_pricing_id", "left")
			->join("events e", "e.id = ep.event_id", "left")
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
		], true);

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

	public function getNotFollowedEvent($member_id)
	{
		$rs = $this->db->query("SELECT e.name as event_name,ev.* FROM events e
			JOIN members m ON m.id = '$member_id'
			JOIN kategory_members km ON km.id = m.status
			JOIN event_pricing ev ON ev.event_id = e.id AND ev.`condition` = km.kategory
			WHERE ev.id NOT IN (
			SELECT td.event_pricing_id FROM transaction_details td
			JOIN `transaction` tr ON tr.id = td.transaction_id WHERE tr.member_id = '$member_id' AND tr.status_payment != 'expire'
		)");
		return $rs->result_array();
	}

	public function count_participant()
	{
		$this->db->select('*');
		$this->db->from('transaction');
		$this->db->where('status_payment', 'settlement');
		$result = $this->db->get();
		return $result->num_rows();
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
}
