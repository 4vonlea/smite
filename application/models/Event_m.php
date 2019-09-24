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

	public function event_pricings()
	{
		return $this->hasMany('Event_pricing_m', 'event_id');
	}

	/**
	 * @param int | array $event_id
	 * @param $user_status
	 */
	public function validateFollowing($event_id, $user_status)
	{
		$avalaible = true;
		if (is_array($event_id))
			$row = $event_id;
		else {
			$this->load->model("Event_pricing_m");
			$result = $this->Event_pricing_m->findOne(['id' => $event_id]);
			$row = $result->toArray();
		}
		$avalaible = $avalaible && $user_status == $row['condition'];
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
		return $avalaible;
	}

	public function eventAvailableNow()
	{
		$filter = ['show' => '1'];
		$this->load->model("Transaction_m");
		$result = $this->setAlias("t")->find()->select("t.name as event_name,event_pricing.name as name_pricing,event_pricing.price as price_r,event_pricing.id as id_price,")
			->select("condition,condition_date,kategory")
			->where($filter)
			->join("event_pricing", "t.id = event_id")
			->order_by("t.id,event_pricing.name,event_pricing.condition_date")->get();
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

			if ($temp != $row['event_name'] && $avalaible) {
				$index++;
				$return[$index] = [
					'name' => $row['event_name'],
					'category' => $row['kategory'],
					'pricingName' => [
						[
							'name' => $row['name_pricing'],
							'title' => $title,
							'pricing' => [$row['condition'] => ['id' => $row['id_price'], 'price' => $row['price_r'], 'available' => $avalaible]]
						]
					],
					'memberStatus' => [$row['condition']]
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
						'pricing' => [$row['condition'] => ['id' => $row['id_price'], 'price' => $row['price_r'], 'available' => $avalaible]]
					];
					$tempPricing = $row['name_pricing'];
				} else {
					$return[$index]['pricingName'][$pId]['pricing'][$row['condition']] = ['id' => $row['id_price'], 'price' => $row['price_r'], 'available' => $avalaible];
				}
			}

		}
		return $return;

	}

	public function eventVueModel($member_id, $userStatus, $filter = [])
	{
		$filter = array_merge($filter, ['show' => '1']);
		$this->load->model("Transaction_m");
		$result = $this->setAlias("t")->find()->select("t.id as id_event,t.kouta,t.name as event_name,event_pricing.name as name_pricing,event_pricing.price as price_r,event_pricing.id as id_price,td.id as followed,COALESCE(checkout,0) as checkout,tr.status_payment")
			->select("condition,condition_date,kategory")
			->where($filter)
			->join("event_pricing", "t.id = event_id")
			->join("transaction_details td", "td.event_pricing_id = event_pricing.id AND td.member_id = '$member_id'", "left")
			->join("transaction tr", "tr.id = td.transaction_id", "left")
			->order_by("t.id,event_pricing.name,event_pricing.condition_date")->get();
		$count = $this->setAlias("t")->find()->select("t.id as id_event,t.kouta,SUM(IF(tr.status_payment = '".Transaction_m::STATUS_FINISH."',1,0)) as participant")
			->join("event_pricing", "t.id = event_id")
			->join("transaction_details td", "td.event_pricing_id = event_pricing.id", "left")
			->join("transaction tr", "tr.id = td.transaction_id", "left")
			->group_by("t.id")->get()->result_array();
		$koutas = [];
		foreach($count as $row){
			$koutas[$row['id_event']] = $row;
		}
		$return = [];
		$temp = "";
		$tempPricing = "";
		$index = -1;
		$pId = 0;
		$frmt = "d M Y";
		foreach ($result->result_array() as $row) {
			if($koutas[$row['id_event']]['participant'] < $row['kouta']) {
				$avalaible = $this->validateFollowing($row, $userStatus);
			}else{
				$avalaible = false;
			}
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
			$waiting_payment = ($row['checkout'] == 1 && !in_array($row['status_payment'],[Transaction_m::STATUS_FINISH,Transaction_m::STATUS_UNFINISH,Transaction_m::STATUS_EXPIRE,Transaction_m::STATUS_DENY]));
			if ($temp != $row['event_name']) {
				$index++;
				$return[$index] = [
					'id' => $row['id_event'],
					'name' => $row['event_name'],
					'category' => $row['kategory'],
					'kouta' => intval($row['kouta']),
					'participant' => intval($koutas[$row['id_event']]['participant']),
					'followed' => ($row['checkout'] == 1 && $row['followed'] != null && $row['status_payment'] == Transaction_m::STATUS_FINISH),
					'pricingName' => [
						[
							'name' => $row['name_pricing'],
							'title' => $title,
							'pricing' => [$row['condition'] => ['id' => $row['id_price'], 'price' => $row['price_r'], 'available' => $avalaible, 'added' => $added,'waiting_payment'=>$waiting_payment]]
						]
					],
					'memberStatus' => [$row['condition']]
				];
				$tempPricing = $row['name_pricing'];
				$pId = 0;
				$temp = $row['event_name'];
			} else {
				if($return[$index]['followed'] == false && ($row['checkout'] == 1 && $row['followed'] != null && $row['status_payment'] == Transaction_m::STATUS_FINISH)){
					$return[$index]['followed'] = true;
				}
				if (!in_array($row['condition'], $return[$index]['memberStatus']))
					$return[$index]['memberStatus'][] = $row['condition'];
				if ($tempPricing != $row['name_pricing']) {
					$pId++;
					$return[$index]['pricingName'][$pId] = [
						'name' => $row['name_pricing'],
						'title' => $title,
						'pricing' => [$row['condition'] => ['id' => $row['id_price'], 'price' => $row['price_r'], 'available' => $avalaible, 'added' => $added,'waiting_payment'=>$waiting_payment]]
					];
					$tempPricing = $row['name_pricing'];
				} else {
					if ($row['checkout'] == 0 || $waiting_payment)
					$return[$index]['pricingName'][$pId]['pricing'][$row['condition']] = ['id' => $row['id_price'], 'price' => $row['price_r'], 'available' => $avalaible, 'added' => $added,'waiting_payment'=>$waiting_payment];
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
		$temp = $this->db->get()->result();
		$result['data'] = array();

		foreach ($temp as $data) {
			$data->kondisi = $this->get_kondisi($data->kategori);
			$result['data'][] = $data;
			$temp2 = $data->kondisi;
			foreach ($temp2 as $data2) {
				$data2->acara = $this->get_acara($data2->kondisi);
				$result2['data2'][] = $data2;
				$temp3 = $data2->acara;
				foreach ($temp3 as $data3) {
					$data3->pricing = $this->get_pricing($data3->id_acara);
					$result3['data3'][] = $data3;
				}
			}
		}
		// debug($result);
		return $result;

	}

	public function get_kondisi($id)
	{
		$this->db->select('pri.condition as kondisi');
		$this->db->from('event_pricing pri');
		$this->db->join('events eve', 'eve.id = pri.event_id');
		$this->db->where('eve.kategory', $id);
		$this->db->group_by('pri.condition');
		$result = $this->db->get()->result();
		return $result;
	}

	public function get_acara($id)
	{
		$this->db->select('pri.event_id as id_acara, eve.name as nama_acara');
		$this->db->from('event_pricing pri');
		$this->db->join('events eve', 'eve.id = pri.event_id');
		$this->db->where('pri.condition', $id);
		$this->db->group_by('nama_acara');
		$result = $this->db->get()->result();
		return $result;
	}

	/**
	 * @param null $event_id
	 * @return CI_DB_query_builder
	 */
	public function getParticipant()
	{
		$this->load->model("Transaction_m");
		return $this->setAlias("t")->find()
			->select("td.id as td_id, td.checklist as checklist,t.id as event_id,t.name as event_name,t.kategory as event_kategory,t.held_on as event_held_on,t.held_in as event_held_in,t.theme as event_theme,m.*,km.kategory as member_status")
			->join("event_pricing ep", "t.id = ep.event_id")
			->join("transaction_details td", "td.event_pricing_id = ep.id")
			->join("transaction tr", "tr.id = td.transaction_id")
			->join("members m", "m.id = td.member_id")
			->join("kategory_members km", "km.id = m.status")
			->where("tr.status_payment", Transaction_m::STATUS_FINISH);
	}

	public function get_pricing($id)
	{
		$this->db->select('pri.name as jenis_harga,condition_date, substring(pri.condition_date,1, 10) as waktu_mulai, substring(pri.condition_date,12, 10) as waktu_akhir, pri.price as harga');
		$this->db->from('event_pricing pri');
		$this->db->where('pri.event_id', $id);
		$this->db->group_by('jenis_harga');
		$result = $this->db->get()->result_array();

		return $result;
	}

	/**
	 * @param $id
	 * @param $data
	 * @return Dompdf
	 */
	public function exportCertificate($data,$id = null){
		if($id == null)
			$id = $this->id;

		$this->load->model('Settings_m');

		$domInvoice = new Dompdf();
		$propery = json_decode(Settings_m::getSetting("config_cert_$id"),true);
		$html = $this->load->view("template/certificate",[
			'image'=>file_get_contents(APPPATH."uploads/cert_template/$id.txt"),
			'property'=>$propery,
			'data'=>$data
		],true);
		$domInvoice->setPaper("a4","landscape");
		$domInvoice->loadHtml($html);
		$domInvoice->render();
		return $domInvoice;
	}

}
