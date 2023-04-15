<?php


use Dompdf\Dompdf;

class Event_m extends MY_Model
{
	protected $table = "events";

	public function rules()
	{
		return [
			['field' => 'name', 'label' => 'Event Name', 'rules' => 'required'],
			['field' => 'kategory', 'label' => 'Event Category', 'rules' => 'required'],
		];
	}

	public function gridConfig($option = array())
	{
		$countParticipantQuery = "SELECT COUNT(td.id) AS jumlahParticipant, ep.event_id FROM transaction_details td
		JOIN `transaction` tr ON tr.id = td.transaction_id
		JOIN event_pricing ep ON ep.id = td.event_pricing_id
		WHERE tr.status_payment = 'settlement'
		GROUP BY ep.event_id";

		return [
			'relationships' => [
				'countParticipant' => ["({$countParticipantQuery})", 'countParticipant.event_id = t.id', 'left'],
			],
		];
	}

	public function event_pricings()
	{
		return $this->hasMany('Event_pricing_m', 'event_id');
	}

	public function groupByHeldOn()
	{
		$return = [];
		foreach ($this->find()->order_by("kategory,held_on")->get()->result_array() as $row) {
			$heldOn = json_decode($row['held_on'], true);
			$startDate = $heldOn['start'] != "" ? DateTime::createFromFormat('Y-m-d', $heldOn['start'])->format("d M Y") : "";
			$endDate = $heldOn['end'] != "" ? DateTime::createFromFormat('Y-m-d', $heldOn['end'])->format("d M Y") : "";
			$heldOnString = ($startDate == $endDate ? $startDate : "$startDate - $endDate");
			$title = $row['kategory'] . " " . $heldOnString;
			$return[$title]['kategory'] = $row['kategory'];
			$return[$title]['heldOn'] = $heldOnString;
			$return[$title]['held'] = [
				'startDate' => DateTime::createFromFormat('Y-m-d', $heldOn['start']),
				'endDate' => DateTime::createFromFormat('Y-m-d', $heldOn['end'])
			];
			$return[$title]['list'][] = $row;
		}
		return $return;
	}
	public function remainingQouta($event_id = null)
	{
		$filter = "";
		if ($event_id) {
			$filter = "WHERE ep.event_id = $event_id";
		}
		$sql = "SELECT COUNT(t.id) AS reserved, ep.event_id,e.kouta
		FROM `events` e
		JOIN event_pricing ep ON ep.event_id = e.id
		LEFT JOIN transaction_details td ON td.event_pricing_id = ep.id
		LEFT JOIN `transaction` t ON t.id = td.transaction_id AND t.status_payment != 'expired' 
		$filter
		GROUP BY e.id";

		return $this->db->query($sql);
	}

	/**
	 * @param int | array $event_pricing_id
	 * @param $user_status
	 */
	public function validateFollowing($event_pricing_id, $user_status)
	{
		$avalaible = true;
		if (is_array($event_pricing_id))
			$row = $event_pricing_id;
		else {
			$this->load->model("Event_pricing_m");
			$result = $this->Event_pricing_m->findOne(['id' => $event_pricing_id]);
			$row = $result->toArray();
		}

		$avalaible = $avalaible && ($user_status == $row['condition'] || $user_status == 'all');
		$conditionDate = explode(":", $row['condition_date']);
		$now = new DateTime();
		$d1 = DateTime::createFromFormat("Y-m-d", $conditionDate[0]);
		$d2 = DateTime::createFromFormat("Y-m-d H:i", $conditionDate[1] . " 23:59");
		if ($conditionDate[0] == "" && $conditionDate[1] != "") {
			$avalaible = $avalaible && $d2 >= $now;
		} elseif ($conditionDate[1] == "" && $conditionDate[0] != "") {
			$avalaible = $avalaible && $d1 < $now;
		} else {
			$avalaible = $avalaible && ($d1 < $now && $d2 >= $now);
		}
		$reserved = $this->remainingQouta($row['id_event'] ?? $row['event_id'])->row_array();
		$avalaible = $avalaible && ($reserved['reserved'] < $reserved['kouta']);
		return $avalaible;
	}

	public function eventAvailableNow($isManual = false)
	{

		$filter = ($isManual ? [] : ['show' => '1']);
		$this->load->model("Transaction_m");
		$result = $this->setAlias("t")->find()->select("t.id as id_event,
				t.name as event_name,
				event_pricing.name as name_pricing,
				event_pricing.price as price_r,
				event_pricing.price_in_usd as price_in_usd,
				event_pricing.id as id_price,
				evt.name as event_required,
				t.event_required as event_required_id,
				t.held_on
			")
			->select("condition,
				condition_date,
				t.kategory")
			->where($filter)
			->join("event_pricing", "t.id = event_id")
			->join("events evt", "evt.id = t.event_required", 'left')
			->order_by("t.id,event_pricing.name,event_pricing.condition_date")->get();
		$reserved = [];
		foreach ($this->remainingQouta()->result_array() as $row) {
			$reserved[$row['event_id']] = $row;
		}

		$return = [];
		$temp = "";
		$tempPricing = "";
		$index = -1;
		$pId = 0;
		$frmt = "d M Y";
		foreach ($result->result_array() as $row) {
			$avalaible = true;
			$now = new DateTime();
			$conditionDate = explode(":", $row['condition_date']);
			$d1 = DateTime::createFromFormat("Y-m-d", $conditionDate[0]);
			$d2 = DateTime::createFromFormat("Y-m-d H:i", $conditionDate[1] . " 23:59");
			if ($conditionDate[0] == "" && $conditionDate[1] != "") {
				$avalaible = $avalaible && $d2 >= $now;
			} elseif ($conditionDate[1] == "" && $conditionDate[0] != "") {
				$avalaible = $avalaible && $d1 < $now;
			} else {
				$avalaible = $avalaible && ($d1 < $now && $d2 >= $now);
			}
			if ($temp != $row['event_name'] || $tempPricing != $row['name_pricing']) {
				$title = "$row[name_pricing] <br/>";
				if ($conditionDate[0] == "" && $conditionDate[1] != "") {
					$title .= " < " . $d2->format($frmt);
				} elseif ($conditionDate[1] == "" && $conditionDate[0] != "") {
					$title .= " > " . $d1->format($frmt);
				} else {
					$title .= $d1->format($frmt) . " - " . $d2->format($frmt);
				}
			}
			$qouta = $reserved[$row['id_event']]['kouta'] ?? 0;
			$booked = $reserved[$row['id_event']]['reserved'] ?? 0;
			$remainingQuouta = $qouta - $booked;
			$avalaible = $avalaible && ($remainingQuouta > 0);

			if ($temp != $row['event_name'] && $avalaible) {
				$index++;
				$return[$index] = [
					'id' => $row['id_event'],
					'name' => $row['event_name'],
					'category' => $row['kategory'],
					'event_required' => $row['event_required'],
					'event_required_id' => $row['event_required_id'],
					'pricingName' => [
						[
							'name' => $row['name_pricing'],
							'title' => $title,
							'pricing' => [
								$row['condition'] => [
									'id' => $row['id_price'],
									'id_event' => $row['id_event'],
									'event_required' => $row['event_required'],
									'event_required_id' => $row['event_required_id'],
									'price' => $row['price_r'] ?? 0,
									'price_in_usd' => $row['price_in_usd'] ?? 0,
									'available' => $avalaible
								]
							]
						]
					],
					'memberStatus' => [$row['condition']],
					'held_on' => $row['held_on'],
					'categoryGroup' => $row['kategory'],
				];
				$tempPricing = $row['name_pricing'];
				$pId = 0;
				$temp = $row['event_name'];
			} elseif ($avalaible) {
				if (!in_array($row['condition'], $return[$index]['memberStatus']))
					$return[$index]['memberStatus'][] = $row['condition'];
				if ($tempPricing != $row['name_pricing']) {
					$pId++;
					$return[$index]['pricingName'][$pId] = [
						'name' => $row['name_pricing'],
						'title' => $title,
						'pricing' => [
							$row['condition'] => [
								'id' => $row['id_price'],
								'id_event' => $row['id_event'],
								'event_required' => $row['event_required'],
								'event_required_id' => $row['event_required_id'],
								'price' => $row['price_r'] ?? 0,
								'price_in_usd' => $row['price_in_usd'] ?? 0,
								'available' => $avalaible
							]
						]
					];
					$tempPricing = $row['name_pricing'];
				} else {
					$return[$index]['pricingName'][$pId]['pricing'][$row['condition']] = [
						'id' => $row['id_price'],
						'id_event' => $row['id_event'],
						'event_required' => $row['event_required'],
						'event_required_id' => $row['event_required_id'],
						'price' => $row['price_r'] ?? 0,
						'price_in_usd' => $row['price_in_usd'] ?? 0,
						'available' => $avalaible
					];
				}
			}
		}
		usort($return, function ($a, $b) {
			return $a['name'] <=> $b['name'];
		});
		return $return;
	}

	public function eventVueModel($member_id = '', $userStatus = '', $filter = [], $onLyFollowed = false)
	{
		if ($onLyFollowed == false) {
			$filter = array_merge($filter, ['show' => '1']);
		}
		$this->load->model("Transaction_m");
		$result = $this->setAlias("t")->find()->select("t.id as id_event,
				t.kouta,
				t.name as event_name,
				event_pricing.name as name_pricing,
				event_pricing.price as price_r,
				event_pricing.price_in_usd as price_in_usd,
				event_pricing.id as id_price,
				evt.name as event_required,
				t.event_required as event_required_id,
				td.id as followed,
				COALESCE(checkout,0) as checkout,
				tr.status_payment,
				t.held_on
			")
			->select("condition,
				condition_date,
				t.kategory,
				t.special_link")
			->where($filter)
			->or_where("td.id IS NOT NULL", null)
			->join("event_pricing", "t.id = event_id")
			->join("events evt", "evt.id = t.event_required", 'left')
			->join("transaction_details td", "td.event_pricing_id = event_pricing.id AND td.member_id = '$member_id'", "left")
			->join("transaction tr", "tr.id = td.transaction_id", "left")
			->order_by("t.id,t.kategory,event_pricing.name,event_pricing.condition_date")->get();
		$count = $this->setAlias("t")->find()->select("t.id as id_event,t.kouta,SUM(IF(tr.status_payment != '" . Transaction_m::STATUS_EXPIRE . "',1,0)) as participant")
			->join("event_pricing", "t.id = event_id")
			->join("transaction_details td", "td.event_pricing_id = event_pricing.id", "left")
			->join("transaction tr", "tr.id = td.transaction_id", "left")
			->group_by("t.id")->get()->result_array();
		$koutas = [];
		foreach ($count as $row) {
			$koutas[$row['id_event']] = $row;
		}
		$return = [];
		$temp = "";
		$tempPricing = "";
		$index = -1;
		$pId = 0;
		$frmt = "d M Y";
		foreach ($result->result_array() as $row) {
			$avalaible = $this->validateFollowing($row, $userStatus);
			$conditionDate = explode(":", $row['condition_date']);
			$d1 = DateTime::createFromFormat("Y-m-d", $conditionDate[0]);
			$d2 = DateTime::createFromFormat("Y-m-d H:i", $conditionDate[1] . " 23:59");

			if ($temp != $row['event_name'] || $tempPricing != $row['name_pricing']) {
				$title = "$row[name_pricing] <br/>";
				if ($conditionDate[0] == "" && $conditionDate[1] != "") {
					$title .= " < " . $d2->format($frmt);
				} elseif ($conditionDate[1] == "" && $conditionDate[0] != "") {
					$title .= " > " . $d1->format($frmt);
				} else {
					$title .= $d1->format($frmt) . " - " . $d2->format($frmt);
				}
			}
			$added = ($row['followed'] != null && $row['checkout'] == 0 ? 1 : 0);
			$waiting_payment = ($row['checkout'] == 1 && !in_array($row['status_payment'], [Transaction_m::STATUS_FINISH, Transaction_m::STATUS_UNFINISH, Transaction_m::STATUS_EXPIRE, Transaction_m::STATUS_DENY]));
			if ($temp != $row['event_name'] && $avalaible) {
				$index++;
				$return[$index] = [
					'id' => $row['id_event'],
					'name' => $row['event_name'],
					'event_required' => $row['event_required'],
					'event_required_id' => $row['event_required_id'],
					'category' => $row['kategory'],
					'special_link' => ($row['special_link'] != "" && $row['special_link'] != "null" ? json_decode($row['special_link']) : []),
					'kouta' => intval($row['kouta']),
					'participant' => intval($koutas[$row['id_event']]['participant']),
					'followed' => ($row['checkout'] == 1 && $row['followed'] != null && $row['status_payment'] == Transaction_m::STATUS_FINISH),
					'pricingName' => [
						[
							'name' => $row['name_pricing'],
							'title' => $title,
							'pricing' => [
								$row['condition'] => [
									'id' => $row['id_price'],
									'id_event' => $row['id_event'],
									'event_required' => $row['event_required'],
									'event_required_id' => $row['event_required_id'],
									'price' => $row['price_r'],
									'price_in_usd' => $row['price_in_usd'],
									'available' => $avalaible,
									'added' => $added,
									'waiting_payment' => $waiting_payment
								]
							]
						]
					],
					'held_on' => $row['held_on'],
					'categoryGroup' => $row['kategory'],
					'hasCertificate' => file_exists(APPPATH . "uploads/cert_template/$row[id_event].txt"),
					'memberStatus' => [$row['condition']]
				];
				$tempPricing = $row['name_pricing'];
				$pId = 0;
				$temp = $row['event_name'];
			} else if ($avalaible) {
				if ($return[$index]['followed'] == false && ($row['checkout'] == 1 && $row['followed'] != null && $row['status_payment'] == Transaction_m::STATUS_FINISH)) {
					$return[$index]['followed'] = true;
				}
				if (!in_array($row['condition'], $return[$index]['memberStatus']))
					$return[$index]['memberStatus'][] = $row['condition'];
				if ($tempPricing != $row['name_pricing']) {
					$pId++;
					$return[$index]['pricingName'][$pId] = [
						'name' => $row['name_pricing'],
						'title' => $title,
						'pricing' => [
							$row['condition'] => [
								'id' => $row['id_price'],
								'price' => $row['price_r'],
								'price_in_usd' => $row['price_in_usd'],
								'available' => $avalaible,
								'added' => $added,
								'waiting_payment' => $waiting_payment
							]
						]
					];
					$tempPricing = $row['name_pricing'];
				} else {
					if ($row['checkout'] == 0 || $waiting_payment || in_array($row['status_payment'], [Transaction_m::STATUS_EXPIRE, Transaction_m::STATUS_DENY]))
						$return[$index]['pricingName'][$pId]['pricing'][$row['condition']] = [
							'id' => $row['id_price'],
							'price' => $row['price_r'],
							'price_in_usd' => $row['price_in_usd'],
							'available' => $avalaible,
							'added' => $added,
							'waiting_payment' => $waiting_payment
						];
				}
			}
		}
		return $return;
	}

	public function listcategory()
	{
		$this->db->select('eve.kategory as kategori, pri.condition as kondisi');
		$this->db->from('events eve');
		$this->db->join('event_pricing pri', 'eve.id = pri.event_id', 'left');
		$this->db->group_by('kategori');
		$this->db->order_by('kategori');
		$temp = $this->db->get()->result();
		$result['data'] = array();

		foreach ($temp as $data) {
			$data->kondisi = $this->get_kondisi($data->kategori);
			$result['data'][] = $data;
			$temp2 = $data->kondisi;
			foreach ($temp2 as $data2) {
				$data2->acara = $this->get_acara($data2->kondisi, $data2->kategor);
				$result2['data2'][] = $data2;
				$temp3 = $data2->acara;
				foreach ($temp3 as $data3) {
					$data3->pricing = $this->get_pricing($data3->id_acara, $data3->kond);
					$result3['data3'][] = $data3;
				}
			}
		}
		// debug($result);
		return $result;
	}

	public function get_kondisi($id)
	{
		$this->db->select('pri.id, pri.condition as kondisi, eve.kategory as kategor');
		$this->db->from('event_pricing pri');
		$this->db->join('events eve', 'eve.id = pri.event_id');
		$this->db->where('eve.kategory', $id);
		$this->db->where('pri.show', '1');
		$this->db->group_by('pri.condition');
		$result = $this->db->get()->result();
		return $result;
	}

	public function get_acara($id, $id2)
	{
		$this->db->select('pri.id, pri.event_id as id_acara, eve.name as nama_acara, pri.condition as kond, eve.kategory as katego');
		$this->db->from('event_pricing pri');
		$this->db->join('events eve', 'eve.id = pri.event_id');
		$this->db->where('pri.condition', $id);
		$this->db->where('eve.kategory', $id2);
		$this->db->where('pri.show', "1");
		$this->db->group_by('nama_acara');
		$this->db->order_by('katego');
		$result = $this->db->get()->result();
		return $result;
	}

	/**
	 * @param null $event_id
	 * @return CI_DB_query_builder
	 */
	public function getParticipant($select = null)
	{
		$this->load->model("Transaction_m");
		return $this->setAlias("t")->find()
			->select($select ?? "m.fullname,m.email,m.phone,m.id as m_id,td.id as td_id, td.checklist as checklist,t.id as event_id,t.name as event_name,t.kategory as event_kategory,t.held_on as event_held_on,t.held_in as event_held_in,t.theme as event_theme,m.*,ep.condition as status_member,km.kategory as member_status,m.alternatif_status,m.alternatif_status2")
			->join("event_pricing ep", "t.id = ep.event_id")
			->join("transaction_details td", "td.event_pricing_id = ep.id")
			->join("transaction tr", "tr.id = td.transaction_id")
			->join("members m", "m.id = td.member_id")
			->join("kategory_members km", "km.id = m.status")
			->where("tr.status_payment", Transaction_m::STATUS_FINISH);
	}

	/**
	 * @param null $event_id
	 * @return CI_DB_query_builder
	 */
	public function getEventWithCountParticipant()
	{
		$this->load->model("Transaction_m");
		return $this->setAlias("t")->find()
			->select("t.id as event_id,t.name as event_name,t.kategory as event_kategory,t.held_on as event_held_on,t.held_in as event_held_in,t.theme as event_theme,COUNT(tr.id) as countParticipant")
			->join("event_pricing ep", "t.id = ep.event_id")
			->join("transaction_details td", "td.event_pricing_id = ep.id", "left")
			->join("transaction tr", "tr.id = td.transaction_id AND tr.status_payment = '" . Transaction_m::STATUS_FINISH . "'", "left")
			->join("members m", "m.id = td.member_id", "left")
			->join("kategory_members km", "km.id = m.status", "left")
			->group_by("t.id");
	}
	public function get_pricing($id, $id2)
	{
		$this->db->select('pri.id, pri.name as jenis_harga,condition_date, substring(pri.condition_date,1, 10) as waktu_mulai, substring(pri.condition_date,12, 10) as waktu_akhir, pri.price as harga');
		$this->db->from('event_pricing pri');
		$this->db->where('pri.event_id', $id);
		$this->db->where('pri.condition', $id2);
		$this->db->group_by('jenis_harga');
		$this->db->order_by('pri.id');
		$result = $this->db->get()->result_array();

		return $result;
	}

	/**
	 * @param $hasheId hash SHA1 ID Transaction Details
	 */
	public function viewCertificate($hashedId)
	{
		$tr = $this->db->from("transaction_details")->join("event_pricing", "event_pricing.id = event_pricing_id")
			->join("transaction", "transaction.id = transaction_id")
			->join("members", "members.id = transaction_details.member_id")
			->join("kategory_members", "kategory_members.id = status")
			->join("events", "events.id = event_pricing.event_id")
			->where("transaction.status_payment", Transaction_m::STATUS_FINISH)
			->where("sha1(transaction_details.id)", $hashedId)
			->select("transaction_id,members.id,fullname,email,kategory_members.kategory as status_member,alternatif_status,alternatif_status2")
			->select("nik,events.name as event_name,events.id as id_event")->get()->row_array();
		return [
			'sertifikat' => $this->exportCertificate($tr, $tr['id_event']),
			'data' => $tr,
		];
	}

	/**
	 * @param $id
	 * @param $data
	 * @return Dompdf
	 */
	public function exportCertificate($data, $id = null)
	{
		if ($id == null) {
			$id = $this->id;
			$event_name = $this->name ?? "Not Found";
		} else {
			$rs = $this->findOne($id);
			$event_name = $rs->name ?? "Not Found";
		}

		$this->load->model(['Settings_m', 'Transaction_m']);
		require_once APPPATH . "third_party/phpqrcode/qrlib.php";
		if (file_exists(APPPATH . "uploads/cert_template/$id.txt")) {
			$tr = $this->db->from("transaction_details")->join("event_pricing", "event_pricing.id = event_pricing_id")
				->join("transaction", "transaction.id = transaction_id")
				->where("transaction_details.member_id", $data['id'])
				->where("transaction.status_payment", Transaction_m::STATUS_FINISH)
				->where("event_id", $id)->select("transaction_id,transaction_details.id as id_detil")->get()->row();
			$data['qr'] = isset($tr->transaction_id) ? base_url("site/sertifikat/" . sha1($tr->id_detil)) : "-";
			$data['event_name'] = $event_name;
			//$data['status_member'] = "Peserta";
			if (isset($data['status_member']) && in_array($data['status_member'], ["Spesialis", "Residen", "General Practitioner"])) {
				$data['status_member'] = "Peserta";
			}
			$domInvoice = new Dompdf();
			$configuration = json_decode(Settings_m::getSetting("config_cert_$id"), true);
			$html = $this->load->view("template/certificate", [
				'image' => file_get_contents(APPPATH . "uploads/cert_template/$id.txt"),
				'property' => $configuration['property'] ?? [],
				'anotherPage' => $configuration['anotherPage'] ?? [],
				'data' => $data
			], true);
			$domInvoice->setPaper("a4", "landscape");
			$domInvoice->loadHtml($html);
			$domInvoice->render();
			return $domInvoice;
		} else {
			throw new ErrorException("Template of Certificate not found !");
		}
	}

	/**
	 * getRequiredEvent
	 *
	 * description
	 *
	 * @return void
	 */
	public function getRequiredEvent($event_id, $member_id)
	{
		$this->load->model("Transaction_m");
		$this->db->select('e.id,
			e.name,
			t.status_payment')
			->from('events e')
			->join('event_pricing ep', 'ep.event_id = e.id')
			->join('transaction_details td', 'td.event_pricing_id = ep.id')
			->join('transaction t', 't.id = td.transaction_id')
			->where('e.id', $event_id)
			->where("status_payment !=", Transaction_m::STATUS_EXPIRE)
			->where('td.member_id', $member_id);
		return $this->db->get()->row();
	}

	public function saveMap($id, $data)
	{
		return $this->update(['p2kb_mapping' => $data], ['id' => $id], false);
	}

	public function getMapping($id)
	{
		$this->load->model("Category_member_m");
		$map = [];
		$row = $this->find()->where("id", $id)->select("id,name,p2kb_mapping")->get()->row();
		if ($row) {
			$map['id'] = $row->id;
			$map['name'] = $row->name;
			$map['map'] = json_decode($row->p2kb_mapping, true) ?? [];
			$map['statusList'] = $this->Category_member_m->find()->get()->result_array();
			foreach ($map['statusList'] as $status) {
				if (!isset($map['map'][$status['kategory']])) {
					$map['map'][$status['kategory']] = ['aktivitas' => null, 'jenisAktivitas' => null, 'skp' => null];
				}
			}
		}
		return $map;
	}

	public function getDataPushP2KB($transactionDetailId)
	{
		$siteTitle = Settings_m::getSetting("site_title");
		$member = $this->getParticipant()->select("m.p2kb_member_id")->where("td.id", $transactionDetailId)->get()->row_array();
		if (!$member)
			return "Data Member Tidak Ditemukan";

		$event = $this->findOne($member['event_id']);
		$row = $this->find()->where("id", $member['event_id'])->select("id,name,p2kb_mapping")->get()->row();

		if ($member['p2kb_member_id'] == "")
			return "Member tidak terdaftar pada database P2KB";

		$ref_member_id = $member['p2kb_member_id'];

		$map = json_decode($row->p2kb_mapping, true) ?? [];
		if (!isset($map[$member['status_member']]['aktivitas']['aktivitas_code']))
			return "Mapping Aktivitas untuk status $member[status_member] tidak ditemukan, Harap cek kembali";
		//$aktivitasCode = $map[$member['status_member']]['aktivitas']['aktivitas_code'];

		if (!isset($map[$member['status_member']]['nilaiSkp']['skp']))
			return "Mapping Nilai SKP untuk status $member[status_member] tidak ditemukan, Harap cek kembali";
		$skp =  $map[$member['status_member']]['nilaiSkp']['skp'];
		$roleEvent =  $map[$member['status_member']]['nilaiSkp']['ref_role_code'];
		$option =  $map[$member['status_member']]['nilaiSkp']['role_id'];
		$judul = $event['name'];
		$lokasi = $event['held_in'];
		$date = json_decode($event['held_on'], true) ?? ['start' => '', 'end' => ''];
		$noRegistrasi = "TD-" . $transactionDetailId;
		$certificate = $this->exportCertificate($member, $member['event_id']);
		file_put_contents("./application/cache/$transactionDetailId.pdf", $certificate->output());

		return [
			"select_event" => $roleEvent, // "101",
			"judul" => $judul, // "Panitia PINPERDOSSI",
			"acara" => $siteTitle, // "Perdossi 2020",
			"lokasi" => $lokasi,
			"tgl_mulai" => $date['start'] ?? "",
			"tgl_selesai" => $date['end'] ?? "",
			"no_registrasi" => $noRegistrasi, //"PINCI-01",
			"ref_member_id" => 3639, // $ref_member_id,
			"skp" => $skp, // "5",
			"usr_crt" => $noRegistrasi, //"PINCI-01",
			"usr_upd" => "generate-event-pin-" . $noRegistrasi,
			"option" => $option,
			"berkas" => base64_encode($certificate->output())
		];
	}
}
