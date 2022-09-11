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


    public function listPricingCategory()
    {
        return $this->db->select("name")->distinct(true)
            ->from("event_pricing")
            ->get()->result_array();
    }
}
