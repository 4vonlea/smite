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
        $return = [];
        $ind = 0;
        foreach($datas['event_pricing'] as $i=>$row){
            if($id == 0 ){
                foreach($row['price'] as $j=>$price){
                    $return['name'][$ind] = $row['name'];
                    $return['condition'][$ind] =  $price['condition'];
                    $return['condition_date'][$ind] =  $row['condition_date'];
                    $return['price'][$ind] =  $price['price'];
                    $ind++;
                }
            }else {
                foreach($row['price'] as $j=>$price){
                    $return[] = [
                        'name' =>  $row['name'],
                        'condition'=>$price['condition'],
                        'condition_date'=>$row['condition_date'],
                        'price'=> $price['price'],
                        'event_id'=>$id
                    ];
                }
            }
        }
        return $return;
    }
}