<?php


class Event_m extends MY_Model
{
	protected $table = "events";

	public function rules()
	{
		return [
			['field' => 'name', 'label' => 'Event Name', 'rules' => 'required'],
			['field' => 'kategory', 'label' => 'Event Category', 'rules' => 'required'],
		];
	}

	public function event_pricings()
	{
		return $this->hasMany('Event_pricing_m', 'event_id');
	}

	/**
	 * @param int | array $event_id
	 * @param $user_status
	 */
	public function validateFollowing($event_id,$user_status){
		$avalaible = true;
		if(is_array($event_id))
			$row = $event_id;
		else{
			$this->load->model("Event_pricing_m");
			$result = $this->Event_pricing_m->findOne(['id'=>$event_id]);
			$row = $result->toArray();
		}
		$avalaible =  $avalaible && $user_status == $row['condition'];
		$conditionDate = explode(":",$row['condition_date']);
		$now = new DateTime();
		$d1 = DateTime::createFromFormat("Y-m-d", $conditionDate[0]);
		$d2 = DateTime::createFromFormat("Y-m-d H:i", $conditionDate[1]." 23:59");
		if($conditionDate[0] == "" && $conditionDate[1] != ""){
			$avalaible = $avalaible && $d2 >= $now;
		}elseif($conditionDate[1] == "" && $conditionDate[0] != ""){
			$avalaible = $avalaible && $d1 <  $now;
		}else{
			$avalaible = $avalaible && ($d1 <  $now && $d2 >= $now);
		}
		return $avalaible;
	}

	public function eventVueModel($member_id,$userStatus, $filter = [])
	{
		$result = $this->setAlias("t")->find()->select("*,t.name as event_name,event_pricing.name as name_pricing,event_pricing.price as price_r,event_pricing.id as id_price,,td.id as followed,checkout")
			->where($filter)
			->join("event_pricing", "t.id = event_id")
			->join("transaction_details td","td.event_pricing_id = event_pricing.id AND td.member_id = '$member_id'","left")
			->join("transaction tr","tr.id = td.transaction_id","left")
			->order_by("t.id,event_pricing.name,event_pricing.condition_date")->get();
		$return = [];
		$temp = "";$tempPricing = "";
		$index = -1;$pId = 0;$frmt = "d M Y";
		foreach ($result->result_array() as $row) {
			$avalaible = $this->validateFollowing($row,$userStatus);
			$conditionDate = explode(":",$row['condition_date']);
			$d1 = DateTime::createFromFormat("Y-m-d", $conditionDate[0]);
			$d2 = DateTime::createFromFormat("Y-m-d H:i", $conditionDate[1]." 23:59");

			if($temp != $row['event_name'] || $tempPricing != $row['name_pricing']){
				$title = "$row[name_pricing] <br/>";
				if($conditionDate[0] == "" && $conditionDate[1] != ""){
					$title.= " < ".$d2->format($frmt);
				}elseif($conditionDate[1] == "" && $conditionDate[0] != ""){
					$title.= " > ".$d1->format($frmt);
				}else{
					$title.= $d1->format($frmt)." - ".$d2->format($frmt);
				}
			}

			if ($temp != $row['event_name']) {
				$index++;
				$return[$index] = [
					'name' => $row['event_name'],
					'category' => $row['kategory'],
					'followed' => ($row['checkout'] == 1 && $row['followed'] != null),
					'pricingName' => [
						[
							'name' => $row['name_pricing'],
							'title' => $title,
							'pricing' => [$row['condition'] => ['id'=>$row['id_price'],'price' => $row['price_r'], 'available' =>$avalaible,'added'=>($row['followed'] != null ? 1:0)]]
						]
					],
					'memberStatus'=>[$row['condition'] ]
				];
				$tempPricing = $row['name_pricing'];
				$pId = 0;
				$temp = $row['event_name'];
			}else{
				if(!in_array($row['condition'],$return[$index]['memberStatus']))
					$return[$index]['memberStatus'][] = $row['condition'];
				if($tempPricing != $row['name_pricing']){
					$pId++;
					$return[$index]['pricingName'][$pId] = 	[
						'name' => $row['name_pricing'],
						'title' => $title,
						'pricing' => [$row['condition'] => ['id'=>$row['id_price'],'price' => $row['price_r'], 'available' => $avalaible,'added'=>($row['followed'] != null ? 1:0)]]
					];
					$tempPricing = $row['name_pricing'];
				}else{
					$return[$index]['pricingName'][$pId]['pricing'][$row['condition']] = ['id'=>$row['id_price'],'price' => $row['price_r'], 'available' => $avalaible,'added'=>($row['followed'] != null ? 1:0)];
				}
			}

		}
		return $return;
	}

}
