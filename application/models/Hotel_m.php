<?php
class Hotel_m extends MY_Model
{
    protected $table = "hotels";
    protected $primaryKey = "id";

    public $fillable = ['name','address'];
    protected $timestamps = false;

    public function gridConfig($option = array())
	{
		return [
			'select'=>['t_id'=>'t.id',"name"=>'t.name',"address"=>'t.address','roomsCount'=>'count(rooms.id)'],
			'relationships' => [
				'rooms' => ['rooms', 'hotel_id = t.id','left'],
			],
			'group_by'=>'t.id',
		];
	}

    public function withRooms($id){
        $hotel = $this->db->where("id",$id)->get($this->table)->row_array();
        if($hotel){
            $hotel['rooms'] = [];
            foreach($this->db->where("hotel_id",$id)->get("rooms")->result_array() as $row){
                $row['range_date'] = [
                    DateTime::createFromFormat("Y-m-d",$row['start_date'])->format("d M Y"),
                    DateTime::createFromFormat("Y-m-d",$row['end_date'])->format("d M Y"),
                ];
                $hotel['rooms'][] = $row;
            }
            return $hotel;
        }
        return null;
    }

    public function summaryBooking(){
        $query = 'SELECT td.transaction_id,td.id,CONCAT("H",h.id) as hotel_id,CONCAT("R",r.id) as room_id,h.name AS hotel,r.name,r.quota, c.date AS booking_date, td.checkin_date,td.checkout_date,t.status_payment FROM transaction_details td
        JOIN `transaction` t ON t.id = td.transaction_id
        JOIN rooms r ON r.id = td.room_id
        JOIN hotels h ON h.id = r.hotel_id
        JOIN temp_calendar c ON td.checkin_date <= c.date AND c.date < td.checkout_date
        WHERE td.event_pricing_id = -1 AND t.status_payment != "expired"';
        $result = $this->db->query($query)->result_array();
        $rangeDate = $this->db->get("temp_calendar")->result_array();
        $roomList = $this->db->join("hotels","hotels.id = hotel_id")
                            ->select("hotels.id as hotel_id,hotels.name as hotel_name,rooms.id as room_id,rooms.name as room_name,quota")
                            ->get("rooms")->result_array();
        $temp = [];
        foreach($result as $row){
            if(!isset($temp[$row['booking_date']][$row['hotel_id']][$row['room_id']])){
                $temp[$row['booking_date']][$row['hotel_id']][$row['room_id']]['waiting'] = 0;
                $temp[$row['booking_date']][$row['hotel_id']][$row['room_id']]['pending'] = 0;
                $temp[$row['booking_date']][$row['hotel_id']][$row['room_id']]['settlement'] = 0;
                $temp[$row['booking_date']][$row['hotel_id']][$row['room_id']]['sum'] = 0;
            }
            $temp[$row['booking_date']][$row['hotel_id']][$row['room_id']][$row['status_payment']]++;
            $temp[$row['booking_date']][$row['hotel_id']][$row['room_id']]['sum']++;
        }
        $hotelAvailable = [];
        foreach($roomList as $row){
            foreach($rangeDate as $date){
                if(!isset($hotelAvailable[$row['hotel_id']])){
                    $hotelAvailable[$row['hotel_id']]['name'] = $row['hotel_name'];
                    $hotelAvailable[$row['hotel_id']]['qouta'] = $row['quota'];
                    $hotelAvailable[$row['hotel_id']]['booked'] = $temp[$date['date']]["H".$row['hotel_id']]["R".$row['room_id']]['sum'] ?? 0;
                }else{
                    $hotelAvailable[$row['hotel_id']]['qouta'] += $row['quota'];
                    $hotelAvailable[$row['hotel_id']]['booked'] += $temp[$date['date']]["H".$row['hotel_id']]["R".$row['room_id']]['sum'] ?? 0;
                }    
            }
        }
        return ['summary'=>$temp,'rangeDate'=>$rangeDate,'roomList'=>$roomList,'availableHotel'=>$hotelAvailable];
    }
}
