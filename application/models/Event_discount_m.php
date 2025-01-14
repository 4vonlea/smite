<?php
class Event_discount_m extends MY_Model
{
    protected $table = "event_discount";
    protected $primaryKey = "id";
    protected $timestamps = false;


    public function rules($ruleCategory = []){
        $rules = [
			['field' => 'name', 'label' => 'Discount Name', 'rules' => 'required|max_length[250]'],
			['field' => 'discount', 'label' => 'Discount Nominal', 'rules' => 'required|numeric'],
			['field' => 'event_combination[pricingCategory]', 'label' => 'Pricing Category', 'rules' => 'required'],
			
			
        ];
        foreach($ruleCategory as $ind => $row){
            $rules[] = ['field' => "event_combination[ruleCategory][$ind][key]", 'label' => 'Event Category', 'rules' => 'required'];
            $rules[] = ['field' => "event_combination[ruleCategory][$ind][val]", 'label' => 'Minimum followed', 'rules' => 'required|numeric'];
        }
        return $rules;
    }

    public function listEvent(){
        return $this->db->select("id,name")
            ->from("events")
            ->get()->result_array();
    }


    public function listPricingCategory()
    {
        return $this->db->select("name")->distinct(true)
            ->from("event_pricing")
            ->get()->result_array();
    }

    public function getLikeEvent(){
		$result = [];
		foreach($this->findAll() as $row){
            $combination = json_decode($row['event_combination'],true);
            $result[] = [
                'event_name'=>'Discount : '.$row['name'],
                'event_id'=>'-2',
                'id'=>'-2',
                'name'=>$combination['pricingCategory'] ?? "",
                'price'=>'-'.$row['discount'],
                'price_in_usd'=>'-'.($row['discount_usd'] ?? "0"),
                "condition"=>$combination['pricingCategory'] ?? "",
            ];
        }
        return $result;
	}
}
