<?php


class Transaction_detail_m extends MY_Model
{
	protected $primaryKey = "id";
	protected $table = "transaction_details";
	public $fillable = ['id','member_id','transaction_id','event_pricing_id','product_name','price','checklist','price_usd'];

	const DATE_KHUSUS = "2022-11-18";
	public function member()
	{
		return $this->hasOne('Member_m', 'id', 'member_id');
	}

	public function sumPriceDetail($transaction_id){
		return $this->find()->select_sum("price")
			->where("transaction_id",$transaction_id)
			->get()->row_object()->price ?? 0;
	}

	public function deleteItem($id){
		$this->load->model("Transaction_m");
		$check = $this->db->query("SELECT COUNT(*) AS countRequired,td.transaction_id FROM transaction_details td
			JOIN event_pricing ev ON ev.id = td.event_pricing_id
			JOIN transaction_details tdo ON tdo.member_id = td.member_id
			JOIN `transaction` `tro` ON tro.id = tdo.transaction_id AND tro.status_payment != 'expired'
			JOIN event_pricing evo ON evo.id = tdo.event_pricing_id
			JOIN `events` evs ON evs.event_required = ev.event_id AND evs.id = evo.event_id
			WHERE td.id = ?",[$id])->row_array();
		if($check == null){
			return ['status'=>false,'message'=>"Item tidak ditemukan !"];
		}
		if($check['countRequired'] > 0){
			return ['status'=>false,'message'=>"Event tidak bisa dihapus, karena merupakan event wajib"];
		}

		$this->find()->where(['id'=>$id])->delete();
		$count = $this->find()->select("SUM(price) as c")
			->where('transaction_id',  $check['transaction_id'])
			->where('event_pricing_id >', "0")
			->get()->row_array();
		if ($count['c'] == 0) {
			$this->find()->where(['event_pricing_id' => 0, 'transaction_id' => $check['transaction_id']])->delete();
		}
		$this->Transaction_m->setDiscount($check['transaction_id']);
		return ['status'=>true,'message'=>''];
	}

	
	public function isOverlapEvent($event_id,$member_id){
		$this->load->model("Event_m");
		$event = $this->Event_m->findOne($event_id);
		$heldOn = json_decode($event->held_on,true) ?? ['start'=>'-','end'=>''];
		return $this->db->from("transaction_details td")
			->join("transaction tr", "tr.id = td.transaction_id")
			->join("event_pricing", "event_pricing.id = event_pricing_id")	
			->join("events","events.id = event_pricing.event_id")
			->where("status_payment !=", Transaction_m::STATUS_EXPIRE)
			->where('STR_TO_DATE(JSON_EXTRACT(held_on,"$.end"),\'"%Y-%m-%d"\') >=', $heldOn['start'])
			->where('STR_TO_DATE(JSON_EXTRACT(held_on,"$.start"),\'"%Y-%m-%d"\') <=', $heldOn['end'])
			->where("td.member_id",$member_id)->count_all_results() > 0;
	}

	public function validateAddEvent($event_pricing_id,$member_id,$memberStatus = ""){
		$this->load->model(["Member_m","Event_pricing_m"]);
		$event_pricing = $this->Event_pricing_m->findOne($event_pricing_id);
		$member = $this->Member_m->findOne($member_id);
		$member_status = $member->status_member->kategory ?? $memberStatus;
		$status = $this->checkRequiredEvent($event_pricing->event_id,$member_id);
		if($status !== true){
			return $status;
		}
		$status = $this->Event_m->validateFollowing($event_pricing->toArray(),$member_status);
		if($status === false){
			return lang("qouta_reached");
		}
		$status = $this->isOverlapEvent($event_pricing->event_id,$member_id);
		if($status === true){
			return lang("event_overlap");
		}
		return true;
	}

	public function checkRequiredEvent($event_id,$member_id){
		$this->load->model("Event_m");
		$findEvent = $this->Event_m->findOne(['id' => $event_id]);
		if ($findEvent && $findEvent->event_required && $findEvent->event_required != "0") {
			$cek = $this->Event_m->getRequiredEvent($findEvent->event_required, $member_id);
			$dataEvent = $this->Event_m->findOne(['id' => $findEvent->event_required]);
			if ($cek) {
				if ($cek->status_payment == Transaction_m::STATUS_FINISH) {
					return true;
				} else if (in_array($cek->status_payment, [Transaction_m::STATUS_PENDING])) {
					return "Not Available, please complete the payment !";
				} 
			} else {
				return "You must follow event {$dataEvent->name} to patcipate this event !";
			}
		}
		return true;
	}

	public function bookHotel($transaction_id,$member_id,$datas){
		$this->load->library("form_validation");
		$this->form_validation->set_rules([
			['field'=>'id','label'=>'Room Id','rules'=>'required'],
			['field'=>'checkin','label'=>'Checkin Date','rules'=>'required'],
			['field'=>'checkout','label'=>'Checkout Date','rules'=>'required'],
		]);
		$this->form_validation->set_data($datas);
		if($this->form_validation->run()){
			$checkinDate = new DateTime($datas['checkin']);
			$checkoutDate = new DateTime($datas['checkout']);
			$night = $checkinDate->diff($checkoutDate)->days;
			if($datas['checkin'] == '2022-11-19'){
				return "Tidak bisa melakukan booking ditanggal 19 November, Mohon melakukan check-in sejak tanggal 18 November dengan durasi menginap minimal 2 malam.";
			}
			if($datas['checkin'] == self::DATE_KHUSUS && $night < 2){
				return "Untuk Tanggal 18 November pemesanan minimal 2 malam";
			}
			if($datas['checkout'] == '2022-11-19'){
                return "Tidak diperkenankan checkout di tanggal 19, harap pilih sebelum atau sesudah tanggal 19";
            }
			if($checkoutDate > $checkinDate){
				$this->load->model(["Transaction_m","Room_m"]);
				if($this->Transaction_m->validateBookingHotel($datas['id'],$datas['checkin'],$datas['checkout'])){
					$room = $this->Room_m->findOne(['id' => $datas['id']]);
					if($room){
						$hotel = $room->hotel;
						$product_name = "Booking ".$hotel->name." Room ".$room->name. " ( ".$night." Malam/Checkin ".$checkinDate->format("d M Y").")";
						return $this->insert([
							'transaction_id'=>$transaction_id,
							'member_id'=>$member_id,
							'event_pricing_id'=>-1,
							'product_name'=>$product_name,
							'price'=>$night * $room->price,
							'price_usd'=>0,
							'room_id'=>$datas['id'],
							'checkin_date'=>$datas['checkin'],
							'checkout_date'=>$datas['checkout'],
						]);
					}else{
						return "Room tidak ditemukan di database";
					}

				}else{
					return "Quota untuk room yang anda pilih sudah habis";
				}

			}else{
				return "Tanggal Checkout harus lebih dari tanggal checkin";
			}
		}else{
			return $this->form_validation->error_string();
		}

	}
}
