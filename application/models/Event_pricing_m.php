<?php


class Event_pricing_m extends MY_Model
{
    protected $table = "event_pricing";

    public function rules()
    {
        return [
            ['field'=>'name[]','label'=>'Pricing Name','rules'=>'required'],
            ['field'=>'condition[]','label'=>'Pricing Category Participant','rules'=>'required'],
            ['field'=>'condition_date[]','label'=>'Price Date Applies','rules'=>'required'],
            ['field'=>'price[]','label'=>'Price','rules'=>'required'],
        ];
    }

    /**
     * @param $datas
     * @param int $id default zero and if zero parsing to validation format
     */
    public function parseForm($datas,$id = 0){
    	$this->load->model("Category_member_m");
//		$participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'kategory', 'id');

		$return = [];
        $ind = 0;
        foreach($datas['event_pricing'] as $i=>$row){
            if($id == 0 ){
                foreach($row['price'] as $j=>$price){
                    $return['name'][$ind] = $row['name'];
                    $return['condition'][$ind] =  $price['condition'];//(isset($participantsCategory[$price['condition']])?$participantsCategory[$price['condition']]:"1");
                    $return['condition_date'][$ind] =  $row['condition_date'];
                    $return['price'][$ind] =  $price['price'];
                    $ind++;
                }
            }else {
                foreach($row['price'] as $j=>$price){
                    $return[] = [
                        'id'=> isset($price['id'])?$price['id']:null,
                        'name' =>  $row['name'],
                        'condition'=>$price['condition'],//(isset($participantsCategory[$price['condition']])?$participantsCategory[$price['condition']]:"1"),
                        'condition_date'=>$row['condition_date'],
                        'price'=> $price['price'],
                        'event_id'=>$id
                    ];
                }
            }
        }
        return $return;
    }

    public function reverseParseForm($datas){
		$this->load->model("Category_member_m");
//		$participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory');

		$return = [];
        foreach($datas as $row){
            if(is_object($row))
                $row = $row->toArray();

            if(is_array($row)){
                $conditionDate = explode(":",$row['condition_date']);
                $return[$row['name']]['name'] = $row['name'];
                $return[$row['name']]['condition_date'] = $row['condition_date'];
                $return[$row['name']]['dateFrom'] = (count($conditionDate) > 1?$conditionDate[0]:'');
                $return[$row['name']]['dateTo'] = (count($conditionDate) > 1?$conditionDate[1]:'');
                $return[$row['name']]['price'][] = [
                    'id'=>$row['id'],
                    'condition'=>$row['condition'],//(isset($participantsCategory[$row['condition']])?$participantsCategory[$row['condition']]:"-"),
                    'price'=>$row['price']
                ];
            }
        }
        return array_values($return);
    }
}
