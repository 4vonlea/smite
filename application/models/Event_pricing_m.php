<?php


class Event_pricing_m extends MY_Model
{
    protected $table = "event_pricing";

    public function rules()
    {
        return [
            ['field' => 'name[]', 'label' => 'Pricing Name', 'rules' => 'required'],
            ['field' => 'condition[]', 'label' => 'Pricing Category Participant', 'rules' => 'required'],
            ['field' => 'condition_date[]', 'label' => 'Price Date Applies', 'rules' => 'required'],
            ['field' => 'price[]', 'label' => 'Price', 'rules' => 'required'],
            ['field' => 'price_in_usd[]', 'label' => 'Price in USD', 'rules' => 'required'],
        ];
    }

    /**
     * @param $datas
     * @param int $id default zero and if zero parsing to validation format
     */
    public function parseForm($datas, $id = 0)
    {
        $this->load->model("Category_member_m");
        //		$participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'kategory', 'id');

        $return = [];
        $ind = 0;
        foreach ($datas['event_pricing'] as $i => $row) {
            if ($id == 0 && isset($row['price'])) {
                foreach ($row['price'] as $j => $price) {
                    $return['name'][$ind] = trim($row['name']);
                    $return['condition'][$ind] =  $price['condition']; //(isset($participantsCategory[$price['condition']])?$participantsCategory[$price['condition']]:"1");
                    $return['condition_date'][$ind] =  $row['condition_date'];
                    $return['price'][$ind] =  $price['price'];
                    $return['price_in_usd'][$ind] =  $price['price_in_usd'];
                    $return['show'][$ind] =  ($price['show'] == "true" || $price['show'] == "1" ? "1" : "0");
                    $ind++;
                }
            } else if (isset($row['price'])) {
                foreach ($row['price'] as $j => $price) {
                    $return[] = [
                        'id' => isset($price['id']) ? $price['id'] : null,
                        'name' =>  trim($row['name']),
                        'condition' => $price['condition'], //(isset($participantsCategory[$price['condition']])?$participantsCategory[$price['condition']]:"1"),
                        'condition_date' => $row['condition_date'],
                        'price' => $price['price'],
                        'price_in_usd' => $price['price_in_usd'],
                        'event_id' => $id,
                        'show' => ($price['show'] == "true" || $price['show'] == "1" ? "1" : "0")
                    ];
                }
            } else {
                $this->_errors['price'] = "Bagian Price Per Status tidak boleh kosong";
            }
        }
        return $return;
    }

    public function reverseParseForm($datas)
    {
        $this->load->model("Category_member_m");
        $participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory');
        $return = [];
        $temp = [];
        foreach ($datas as $row) {
            if (is_object($row))
                $row = $row->toArray();

            if (is_array($row)) {
                $conditionDate = explode(":", $row['condition_date']);
                $return[$row['name']]['name'] = $row['name'];
                $return[$row['name']]['condition_date'] = $row['condition_date'];
                $return[$row['name']]['dateFrom'] = (count($conditionDate) > 1 ? $conditionDate[0] : '');
                $return[$row['name']]['dateTo'] = (count($conditionDate) > 1 ? $conditionDate[1] : '');
                $return[$row['name']]['price'][] = [
                    'id' => $row['id'],
                    'condition' => $row['condition'], //(isset($participantsCategory[$row['condition']])?$participantsCategory[$row['condition']]:"-"),
                    'price' => $row['price'],
                    'price_in_usd' => $row['price_in_usd'],
                    'show' => ($row['show'] == 1)
                ];
                $temp[$row['name'] . 'price'][] = $row['condition'];
            }
        }
        foreach ($return as $eventPricing) {
            $notInclude = array_diff($participantsCategory, $temp[$eventPricing['name'] . 'price']);
            foreach ($notInclude as  $condition) {
                $return[$eventPricing['name']]['price'][] = [
                    'condition' => $condition,
                    'price' => 0,
                    'show' => 1
                ];
            }
        }
        return array_values($return);
    }

    public function findWithProductName($filter)
    {
        if (isset($filter['id'])) {
            $filter['event_pricing.id'] = $filter['id'];
            unset($filter['id']);
        }
        $row = $this->find()->where($filter)
            ->join("events e", "e.id = event_pricing.event_id")
            ->select("event_pricing.*,e.held_on,e.name as event_name")
            ->get()->row();
        if ($row) {
            $heldOn = json_decode($row->held_on, true) ?? [];
            $date = "";
            $start = $heldOn['start'] ?? "";
            $end = $heldOn['end'] ?? "";
            if ($start == $end && $start != "") {
                $date .= " (";
                $date .= (new DateTime($start))->format("d M Y");
                $date .= ")";
            } else if ($start != "" && $end != "") {
                $date .= " (";
                $date .= (new DateTime($start))->format("d M Y");
                $date .= " - " . (new DateTime($end))->format("d M Y");
                $date .= ")";
            }
            $row->product_name = "{$row->event_name}{$date} As {$row->condition}";
        }
        return $row;
    }
}
