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
							SUM(IF(JSON_EXTRACT(IF(checklist = '','{}',checklist), '$.nametag') = 'true',1,0)) as nametag,
							SUM(IF(JSON_EXTRACT(IF(checklist = '','{}',checklist), '$.seminarkit') = 'true',1,0)) as seminarkit,
							SUM(IF(JSON_EXTRACT(IF(checklist = '','{}',checklist), '$.certificate') = 'true',1,0)) as certificate
							FROM transaction_details td
						JOIN transaction t ON t.id = td.transaction_id
						JOIN event_pricing ep ON ep.id = td.event_pricing_id
						WHERE t.status_payment = '" . Transaction_m::STATUS_FINISH . "'
						GROUP BY event_id";

		if ($this->session->has_userdata("user_session") && $this->session->user_session['role'] == User_account_m::ROLE_SUPERADMIN) {
			$rs = $this->db->select("t.id as id_event,t.kouta,name,COALESCE(fund_collected,0) as fund_collected,COALESCE(number_participant,0) as number_participant,nametag,seminarkit,certificate")
				->join("( $queryTemp ) as c", "c.id_event = t.id", "left")
				->from("events t")->get();

		} else {
			$rs = $this->db->select("t.id as id_event,t.kouta,name,COALESCE(number_participant,0) as number_participant,nametag,seminarkit,certificate")
				->join("( $queryTemp ) as c", "c.id_event = t.id", "left")
				->from("events t")->get();
		}

		$countByStatus = $this->db->query("SELECT ev.id as event_id, t.kategory as status,ev.name as event, COUNT(m.id) AS jumlah FROM kategory_members t
											LEFT JOIN members m ON t.id = m.`status`
											LEFT JOIN `transaction` tr ON tr.member_id = m.id AND tr.status_payment = 'settlement'
											LEFT JOIN transaction_details dt ON dt.transaction_id = tr.id AND dt.event_pricing_id > 0
											LEFT JOIN `event_pricing` ep ON ep.id = dt.event_pricing_id
											LEFT JOIN `events` ev ON ev.id = ep.event_id
											GROUP BY t.id,ev.id")->result_array();
		$chartResult = [];
		foreach($countByStatus as $row){
			if($row['event'] != ""){
				$chartResult[$row['event_id']]['title'] = $row['event'];
				$chartResult[$row['event_id']]['data']['labels'][] = $row['status'];
				$chartResult[$row['event_id']]['data']['datasets'][0]['label'] = 'Participant';
				$chartResult[$row['event_id']]['data']['datasets'][0]['backgroundColor'] = '#f87979';
				$chartResult[$row['event_id']]['data']['datasets'][0]['data'][] =$row['jumlah'];
			}
		}					
		$participantEvent = $rs->result_array();		
		$adminfee = $this->db->select("0 as id_event,'-'  as kouta,'Admin Fee' as name,SUM(price) as fund_collected, COUNT(dt.id) as number_participant,'-' as nametag,'-' as seminarkit,'-' as certificate")
				->join("transaction t","t.id = dt.transaction_id")
				->where("t.status_payment",Transaction_m::STATUS_FINISH)
				->where("dt.event_pricing_id","0")
				->from("transaction_details dt")->get()->row_array();
		if($adminfee){
			$participantEvent[] = $adminfee;
		}
		$return['charts'] = $chartResult;
		$return['participants_event'] = $participantEvent;
		return $return;
	}

	public function getDataPaper()
	{
		$this->load->model("Papers_m");
		$result = $this->db->select("
				CONCAT(st.value,LPAD(p.id,3,0)) as id_paper,
				fullname as nama,
				univ_nama as universitas,
				kategory_members.kategory as status,
				status_payment,
				title, 
				u.name as reviewer,
				p.status as status_abstract,
				status_fullpaper,
				status_presentasi,
				category_paper.name as manuscript_section,
				methods as manuscrypt_category,
				type,
				type_presence as type_presentation,
				DATE_FORMAT(p.created_at,'%d %M %Y at %H:%i') as submitted_on,
				score,
				DATE_FORMAT(p.updated_at,'%d %M %Y at %H:%i') as reviewed_on")
			->from("papers p")
			->join("members m", "m.id = p.member_id")
			->join("univ","univ = univ_id")
			->join("kategory_members","kategory_members.id = m.status")
			->join("settings st",'st.name = "format_id_paper"')
			->join("category_paper","category = category_paper.id")
			->join("user_accounts u", "u.username = p.reviewer", "left")
			->join('(SELECT t.id, GROUP_CONCAT(DISTINCT CONCAT(t.id,": ",t.status_payment)) AS status_payment,td.member_id FROM transaction_details td
			JOIN transaction t ON t.id = td.transaction_id
			GROUP BY td.member_id) as payment','payment.member_id = m.id','left')
			->get()->result_array();
		foreach ($result as $i => $row) {
			$result[$i]['status_abstract'] = isset(Papers_m::$status[$row['status_abstract']]) ? Papers_m::$status[$row['status_abstract']]:'-';
			$result[$i]['status_fullpaper'] = isset(Papers_m::$status[$row['status_fullpaper']]) ? Papers_m::$status[$row['status_fullpaper']]:'-';
			$result[$i]['status_presentasi'] = isset(Papers_m::$status[$row['status_presentasi']]) ?Papers_m::$status[$row['status_presentasi']]:'-';
		}
		return $result;
	}

	public function getTransaksi()
	{
		$this->load->model("Member_m");
		$this->load->model("Transaction_m");
		$result = $this->db->select("t.id as no_invoice, m.id as member_id, m.fullname as nama, COALESCE(e.name,'-') as acara, COALESCE(td.product_name,'-') as product_name, ep.name, td.price as harga, t.updated_at as waktu_transaksi, t.status_payment as status_pembayaran, t.message_payment as ket ")
			->select("m.sponsor")
			->from("transaction t")
			->join("members m", "m.id = t.member_id")
			->join("transaction_details td", "t.id = td.transaction_id", "left")
			->join("event_pricing ep", "ep.id = td.event_pricing_id", "left")
			->join("events e", "e.id = ep.event_id","left")
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

		$rs = $this->db->select("t.id AS no_invoice,m.id AS id_member, m.fullname,kt.kategory as status, m.gender,m.phone,m.email,m.sponsor,m.city,e.name AS event_name,td.price as price,t.channel as method_payment,t.message_payment as additional_info")
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
					JOIN transaction t ON t.id = td.transaction_id
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
