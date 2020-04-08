<?php


class Dashboard_m extends CI_Model
{

	public function getData()
	{
		$this->load->model("Transaction_m");
		$return = [];
		$rs = $this->db->select("COUNT(m.id) AS total_members,SUM(IF(m.verified_by_admin = 0,1,0)) AS unverified_members, SUM(IF(p.id IS NOT NULL,1,0)) AS participants_paper", false)
			->join("papers p", "p.member_id = m.id", "left")
			->from("members m")->get();
		$return = $rs->row_array();
		$queryTemp = "SELECT 
							event_id AS id_event,
							COUNT(event_id) as number_participant,
							SUM(td.price) AS fund_collected,
							SUM(IF(JSON_EXTRACT(checklist, '$.nametag') = 'true',1,0)) as nametag,
							SUM(IF(JSON_EXTRACT(checklist, '$.seminarkit') = 'true',1,0)) as seminarkit,
							SUM(IF(JSON_EXTRACT(checklist, '$.certificate') = 'true',1,0)) as certificate
							FROM transaction_details td
						JOIN transaction t ON t.id = td.transaction_id
						JOIN event_pricing ep ON ep.id = td.event_pricing_id
						WHERE t.status_payment = '" . Transaction_m::STATUS_FINISH . "'
						GROUP BY event_id";

		if ($this->session->user_session['role'] == User_account_m::ROLE_SUPERADMIN) {
			$rs = $this->db->select("t.id as id_event,t.kouta,name,COALESCE(fund_collected,0) as fund_collected,COALESCE(number_participant,0) as number_participant,nametag,seminarkit,certificate")
				->join("( $queryTemp ) as c", "c.id_event = t.id", "left")
				->from("events t")->get();

		} else {
			$rs = $this->db->select("t.id as id_event,t.kouta,name,COALESCE(number_participant,0) as number_participant,nametag,seminarkit,certificate")
				->join("( $queryTemp ) as c", "c.id_event = t.id", "left")
				->from("events t")->get();

		}

		$return['participants_event'] = $rs->result_array();
		return $return;
	}

	public function getDataPaper()
	{
		$this->load->model("Papers_m");
		$result = $this->db->select("fullname as nama,title, u.name as reviewer,p.status,type,DATE_FORMAT(p.created_at,'%d %M %Y at %H:%i') as submitted_on,DATE_FORMAT(p.updated_at,'%d %M %Y at %H:%i') as reviewed_on")
			->from("papers p")
			->join("members m", "m.id = p.member_id")
			->join("user_accounts u", "u.username = p.reviewer", "left")
			->get()->result_array();
		foreach ($result as $i => $row) {
			$result[$i]['status'] = Papers_m::$status[$row['status']];
		}
		return $result;
	}

	public function getTransaksi()
	{
		$this->load->model("Member_m");
		$this->load->model("Transaction_m");
		$result = $this->db->select("t.id as no_invoice, m.id as member_id, m.fullname as nama, e.name as acara, td.price as harga, t.status_payment as status_pembayaran, t.message_payment as ket ")
			->from("transaction t")
			->join("members m", "m.id = t.member_id")
			->join("transaction_details td", "t.id = td.transaction_id", "left")
			->join("event_pricing ep", "ep.id = td.event_pricing_id")
			->join("events e", "e.id = ep.event_id")
			->order_by("t.id, m.id")
			->get()->result_array();
		return $result;
	}

	public function getMemberEvent()
	{
		$this->load->model("Member_m");
		$this->load->model("Transaction_m");
		$result = $this->db->select("m.id as member_id, fullname as nama, e.name as Acara yang diikuti")
			->from("members m")
			->join("transaction t", "m.id = t.member_id", "left")
			->join("transaction_details td", "t.id = td.transaction_id", "left")
			->join("event_pricing ep", "ep.id = td.event_pricing_id")
			->join("events e", "e.id = ep.event_id")
			->where("t.status_payment", "SETTLEMENT")
			->order_by("m.id")
			->get()->result_array();
		return $result;
	}

//	public function get_event($id) {
//		$this->load->model("Member_m");
//		$this->load->model("Transaction_m");
//		$result = $this->db->select("e.name as Acara")
//			->from("members m")
//			->join("transaction t","m.id = t.member_id", "left")
//			->join("transaction_details td","t.id = td.transaction_id", "left")
//			->join("event_pricing ep", "ep.id = td.event_pricing_id")
//			->join("events e", "e.id = ep.event_id")
//			->where("m.id", $id)
//			->where("t.status_payment", "SETTLEMENT")
//			->get()->result_array();
//		return $result;
//	}

	public function getParticipant($event_id)
	{
		$this->load->model("Transaction_m");

		$rs = $this->db->select("t.id AS no_invoice,m.id AS id_member, m.fullname,kt.kategory as status, m.gender,m.phone,m.email,m.city,e.name AS event_name,CONCAT('Rp ',FORMAT(td.price,2)) as price,t.channel as method_payment,t.message_payment as additional_info")
			->select("IF(JSON_EXTRACT(checklist, '$.nametag') = 'true','Yes','No') as take_nametag,
							IF(JSON_EXTRACT(checklist, '$.seminarkit') = 'true','Yes','No') as take_seminarkit,
							IF(JSON_EXTRACT(checklist, '$.certificate') = 'true','Yes','No') as take_certificate")
			->join("transaction t", "t.id = td.transaction_id")
			->join("members m", "m.id = td.member_id ")
			->join("kategory_members kt", "kt.id = m.status ")
			->join("event_pricing ep", "ep.id = td.event_pricing_id")
			->join("events e", "e.id = ep.event_id")
			->where("event_id", $event_id)->where("status_payment", Transaction_m::STATUS_FINISH)
			->get("transaction_details td");
		return $rs->result_array();
	}

	public function getDataMember()
	{
		$this->load->model(["Transaction_m", "Papers_m"]);
		$query = 'SELECT GROUP_CONCAT(t.event_id SEPARATOR ";") AS event_follow, m.id,m.fullname,m.gender,m.birthday,m.phone,m.email,m.birthday,m.city,m.address,p.`status` AS paper
					FROM members m
					LEFT JOIN (
					SELECT ep.event_id,td.member_id
					FROM
					transaction_details td
					JOIN TRANSACTION t ON t.id = td.transaction_id
					JOIN event_pricing ep ON ep.id = td.event_pricing_id
					WHERE t.status_payment = "' . Transaction_m::STATUS_FINISH . '") AS t ON t.member_id =m.id
					LEFT JOIN papers p ON p.member_id = m.id 
					GROUP BY m.id';
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		$rs = $this->db->get("events");
		$event = $rs->result_array();
		foreach ($data as $i => $row) {
			$follow = explode(";", $row['event_follow']);
			unset($data[$i]['event_follow']);
			foreach ($event as $ev) {
				$data[$i][$ev['name']] = (in_array($ev['id'], $follow) ? "Y" : "N");
			}
			$data[$i]['paper'] = (isset(Papers_m::$status[$row['paper']]) ? Papers_m::$status[$row['paper']] : "N");
		}
		return $data;
	}

}
