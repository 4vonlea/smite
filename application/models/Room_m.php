<?php
class Room_m extends MY_Model
{
    protected $table = "rooms";
    protected $primaryKey = "id";
    public $fillable = ['name','quota','description','start_date','end_date'];
    protected $timestamps = false;


    public function availableRoom($checkin,$checkout){
        $query = $this->db->select("count(room_id) as countBooking,room_id")
            ->from("transaction_details td")
			->join("transaction tr","tr.id = td.transaction_id")
			->where("status_payment !=",Transaction_m::STATUS_EXPIRE)
			->where('td.checkout >',$checkin)
			->where('td.checkin <',$checkout)
            ->group_by("room_id")->get_compiled_select();
            
        $result = $this->db->from($this->table)
            ->join("hotels","hotels.id = rooms.hotel_id")
            ->join("( $query ) as countQuery","countQuery.room_id = rooms.id","left")
            ->where("COALESCE(countBooking,0) <= quota")
            ->where("'$checkin' BETWEEN rooms.start_date AND rooms.end_date")
            ->where("'$checkout' BETWEEN rooms.start_date AND rooms.end_date")
            ->select("rooms.*,hotels.name as hotel_name,hotels.address")
            ->get();
        return $result->result_array();
    }

}
