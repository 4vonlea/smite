<?php
class Room_m extends MY_Model
{
    protected $table = "rooms";
    protected $primaryKey = "id";
    public $fillable = ['name','quota','description','start_date','end_date'];
    protected $timestamps = false;


    public function updateTempCalendar(){
        $range = $this->rangeBooking();
        if($range){
            $this->db->truncate("temp_calendar");
            $currentDate = new DateTime($range['start']);
            $endDate = new DateTime($range['end']);
            while($currentDate <= $endDate){
                $this->db->insert("temp_calendar",['date'=>$currentDate->format("Y-m-d")]);
                $currentDate->modify("+1 days");
            }
        }
    }

    public function rangeBooking(){
        return $this->db->from($this->table)->select("min(start_date) as start,max(end_date) as end")->get()->row_array();
    }


    public function availableRoom($checkin,$checkout){
        $this->load->model("Transaction_m");
        $query = $this->db->select("count(room_id) as countBooking,room_id")
            ->from("transaction_details td")
			->join("transaction tr","tr.id = td.transaction_id")
			->where("status_payment !=",Transaction_m::STATUS_EXPIRE)
			->where('td.checkout_date >',$checkin)
			->where('td.checkin_date <',$checkout)
            ->group_by("room_id")->get_compiled_select();
            
        $result = $this->db->from($this->table)
            ->join("hotels","hotels.id = rooms.hotel_id")
            ->join("( $query ) as countQuery","countQuery.room_id = rooms.id","left")
            ->where("COALESCE(countBooking,0) < quota")
            ->where("'$checkin' BETWEEN rooms.start_date AND rooms.end_date")
            ->where("'$checkout' BETWEEN rooms.start_date AND rooms.end_date")
            ->select("rooms.*,hotels.name as hotel_name,hotels.address")
            ->get();
        return $result->result_array();
    }

    public function bookedRoom($member_id){
        $this->load->model("Transaction_m");
        $result = $this->db->from($this->table)
            ->join("hotels","hotels.id = rooms.hotel_id")
            ->join("transaction_details td","td.room_id = rooms.id")
            ->join("transaction t","t.id = td.transaction_id")
            ->where("td.member_id",$member_id)
            ->where("event_pricing_id","-1")
            ->where("t.status_payment !=",Transaction_m::STATUS_EXPIRE)
            ->select("rooms.id,rooms.name,hotels.name as hotel_name,td.checkin_date as checkin,td.checkout_date as checkout,t.status_payment,td.price")
            ->get();
        $return = [];
        $statusList = [
            'settlement'=>'Booked',
            'waiting'=>'Waiting Checkout',
            'pending'=>'Payment Pending',
            'need_verification'=>'Payment need to verify',
        ];
        foreach($result->result_array() as $row){
            $row['status_payment'] = $statusList[$row['status_payment']] ?? "-";
            $return[] = $row;
        }
        return $return;
    }

    
	public function hotel()
	{
		return $this->hasOne("Hotel_m", "id", "hotel_id");
	}

}
