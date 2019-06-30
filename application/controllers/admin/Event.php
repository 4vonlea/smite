<?php


class Event extends Admin_Controller
{
    public function index(){
        foreach(Settings_m::particantCategory() as $cat){
            $pricingDefault[] = ['condition'=>$cat,'price'=>0];
        }
        $this->load->helper('form');
        $this->layout->render("event",['pricingDefault'=>$pricingDefault]);
    }

    public function grid()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('Event_m');

            $grid = $this->Event_m->gridData($this->input->get());
            $this->output
//                    ->set_content_type("application/json")
                ->_display(json_encode($grid));
        }else{
            $this->output->set_status_header(403);
        }
    }
    public function save(){
        if($this->input->is_ajax_request()){
            $this->load->model(['Event_m','Event_pricing_m']);

            $data = $this->input->post();
            $pricing = $this->Event_pricing_m->parseForm($data);

            $cekEv = $this->Event_m->validate($data);
            $cekEvPrice =  $this->Event_pricing_m->validate($pricing);

            if($cekEv && $cekEvPrice){
                $this->Event_m->getDB()->trans_start();
                $event = $data;
                unset($event['event_pricing']);
                $this->Event_m->insert($event,false);

                $event_id = $this->Event_m->getLastInsertID();
                $pricing = $this->Event_pricing_m->parseForm($data,$event_id);

                $this->Event_pricing_m->batchInsert($pricing,false);
                $this->Event_m->getDB()->trans_complete();
                $this->output->_display(json_encode(['status'=>true,'msg'=>'Data saved successfully !']));
            }else{
                $error = array_merge($this->Event_m->getErrors(),$this->Event_pricing_m->getErrors());
                $this->output->set_status_header(400)
                    ->set_content_type("application/json")
                    ->_display(json_encode($error));
            }
        }else{
            $this->output->set_status_header(403);
        }
    }

}