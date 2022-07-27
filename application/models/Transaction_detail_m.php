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

			if($datas['checkin'] == self::DATE_KHUSUS && $night < 2){
				return "Untuk Tanggal 18 November pemesanan minimal 2 malam";
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
