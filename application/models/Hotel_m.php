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
}
