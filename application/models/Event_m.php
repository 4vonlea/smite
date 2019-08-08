<?php


class Event_m extends MY_Model
{
    protected $table = "events";
    public function rules()
    {
        return [
            ['field'=>'name','label'=>'Event Name','rules'=>'required'],
            ['field'=>'kategory','label'=>'Event Category','rules'=>'required'],
        ];
    }

    public function event_pricings(){
        return $this->hasMany('Event_pricing_m','event_id');
    }

    public function eventVueModel($filter = []){
    	$result = $this->setAlias("t")->find()->select("*,t.name as event_name,event_pricing.name as name_pricing")->where($filter)->join("event_pricing","t.id = event_id")
			->order_by("t.id,event_pricing.name,event_pricing.condition_date")->get();
    	$return = [];
    	$temp = ""; $index = -1;
    	foreach($result->result_array() as $row){
    		if($temp != $row['event_name']){
    			$index++;
				$return[$index] = [
					'name'=>$row['event_name'],
					'category'=>$row['kategory'],
					'pricingName'=>[
						[
							'name'=>$row['name_pricing']
						]
					]
				];
				$temp = $row['event_name'];
			}

		}
    	return $return;
	}

}
