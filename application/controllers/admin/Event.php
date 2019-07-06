<?php

/**
 * Class Event
 * @property Event_m $Event_m
 */
class Event extends Admin_Controller
{
    public function index()
    {
        $this->load->model('Category_member_m');
        $participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory');
        foreach ($participantsCategory as $cat) {
            $pricingDefault[] = ['condition' => $cat, 'price' => 0];
        }
        $this->load->helper('form');
        $this->layout->render("event", ['pricingDefault' => $pricingDefault]);
    }

    public function grid()
    {
        $this->load->model('Event_m');

        $grid = $this->Event_m->gridData($this->input->get());
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode($grid));

    }

    public function delete(){
        if($this->input->is_ajax_request()){
            $id = $this->input->post('id');
            $this->load->model(['Event_m','Event_pricing_m']);
            $this->Event_m->getDB()->trans_start();
            $this->Event_m->delete(['id'=>$id]);
            $this->Event_pricing_m->delete(['event_id'=>$id]);
            $this->Event_m->getDB()->trans_complete();
            if($this->Event_m->getDB()->trans_status() == false)
                $this->output->set_status_header(500);
        }else {
            $this->output->set_status_header(403);
        }
    }

    public function detail(){
        if($this->input->is_ajax_request()){
            $id = $this->input->post('id');
            $this->load->model(['Event_m','Event_pricing_m']);
            $event = $this->Event_m->findOne(['id'=>$id]);
            $data = $event->toArray();
            $data['event_pricing'] = $this->Event_pricing_m->reverseParseForm($event->event_pricings);
            $this->output
                ->set_content_type("application/json")
                ->_display(json_encode($data));
        }else {
            $this->output->set_status_header(403);
        }
    }

    public function save()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model(['Event_m', 'Event_pricing_m']);

            $data = $this->input->post();
            $pricing = $this->Event_pricing_m->parseForm($data);

            $cekEv = $this->Event_m->validate($data);
            $cekEvPrice = $this->Event_pricing_m->validate($pricing);

            if ($cekEv && $cekEvPrice) {
                $this->Event_m->getDB()->trans_start();
                $eventData = $data;
                unset($eventData['event_pricing']);
                $event_id = null;
                if(isset($data['id'])){
                    $event = Event_m::findOne(['id'=>$data['id']]);
                    if(!$event) {
                        $event = new Event_m();
                    }else{
                        $event_id = $data['id'];
                    }
                }else{
                    $event = new Event_m();
                }

                $event->setAttributes($eventData);
                $event->save(false);
//                $this->Event_m->insert($event, false);
                if(!$event_id)
                    $event_id = $this->Event_m->getLastInsertID();

                $pricing = $this->Event_pricing_m->parseForm($data, $event_id);
                foreach($pricing as $row){
                    if(isset($row['id'])){
                        $event_pricing = Event_pricing_m::findOne(['id'=>$row['id']]);
                        if(!$event_pricing)
                            $event_pricing = new Event_pricing_m();
                    }else{
                        $event_pricing = new Event_pricing_m();
                    }
                    $event_pricing->setAttributes($row);
                    $event_pricing->save(false);
//                    $this->Event_pricing_m->batchInsert($pricing, false);

                }
                $this->Event_m->getDB()->trans_complete();
                $this->output->_display(json_encode(['status' => true, 'msg' => 'Data saved successfully !']));
            } else {
                $error = array_merge($this->Event_m->getErrors(), $this->Event_pricing_m->getErrors());
                $this->output->set_status_header(400)
                    ->set_content_type("application/json")
                    ->_display(json_encode($error));
            }
        } else {
            $this->output->set_status_header(403);
        }
    }

}