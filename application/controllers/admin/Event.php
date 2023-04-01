<?php

/**
 * Class Event
 * @property Event_m $Event_m
 * @property CI_Output $output
 */
class Event extends Admin_Controller
{
    protected $accessRule = [
        'index' => 'view',
        'grid' => 'view',
        'delete' => 'delete',
        'detail' => 'view',
        'save' => 'insert',
        'delete_pricing' => 'delete',
    ];

    public function index()
    {
        $this->load->model('Category_member_m');
        $participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory');
        $pricingDefault = [];
        foreach ($participantsCategory as $cat) {
            $pricingDefault[] = ['condition' => $cat, 'price' => 0, 'show' => '1'];
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

    public function delete()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->load->model(['Event_m', 'Event_pricing_m']);
            $this->Event_m->getDB()->trans_start();
            $this->Event_m->delete(['id' => $id]);
            $this->Event_pricing_m->delete(['event_id' => $id]);
            $this->Event_m->getDB()->trans_complete();
            if ($this->Event_m->getDB()->trans_status() == false)
                $this->output->set_status_header(500);
        } else {
            $this->output->set_status_header(403);
        }
    }

    public function detail()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->load->model(['Event_m', 'Event_pricing_m']);
            $event = $this->Event_m->findOne(['id' => $id]);
            $data = $event->toArray();
            $data['special_link'] = $data['special_link'] == "" || $data['special_link'] == "null" ? [] : json_decode($data['special_link']);
            $data['event_pricing'] = $this->Event_pricing_m->reverseParseForm($event->event_pricings);
            $data['held_on'] = json_decode($data['held_on'], true);
            $this->output
                ->set_content_type("application/json")
                ->_display(json_encode($data));
        } else {
            $this->output->set_status_header(403);
        }
    }

    protected function base64ImageSaver($image_data, $path, $filename)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $image_data, $type)) {
            list($typeRaw, $data) = explode(';', $image_data); // exploding data for later checking and validating 
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                return false;
            }

            $data = base64_decode($data);

            if ($data === false) {
                return false;
            }
        } else {
            return false;
        }

        $fullname = $filename . "." . $type;

        if (file_put_contents($path . $fullname, $data) === false) {
            return false;
        }
        /* it will return image name if image is saved successfully 
        or it will return error on failing to save image. */
        return $fullname;
    }

    public function save_session()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('Event_m');
            $event_id = $this->input->post("id");
            $session = $this->input->post("session");
            $this->output
                ->set_content_type("application/json")
                ->_display(json_encode([
                    'status' => $this->Event_m->update(['session' => json_encode($session)], ['id' => $event_id], false)
                ]));
        }
    }

    public function save()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model(['Event_m', 'Event_pricing_m']);

            $data = $this->input->post(null, false);
            $pricing = $this->Event_pricing_m->parseForm($data);

            $cekEv = $this->Event_m->validate($data);
            $cekEvPrice = $this->Event_pricing_m->validate($pricing);

            if ($cekEv && $cekEvPrice) {
                $this->Event_m->getDB()->trans_start();
                $eventData = $data;
                unset($eventData['event_pricing']);
                $event_id = null;
                if (isset($data['id'])) {
                    $event = Event_m::findOne(['id' => $data['id']]);
                    if (!$event) {
                        $event = new Event_m();
                    } else {
                        $event_id = $data['id'];
                    }
                } else {
                    $event = new Event_m();
                }
                $eventData['held_on'] = json_encode($eventData['held_on']);
                $event->setAttributes($eventData);
                $special_link = [];
                if ($this->input->post("special_link")) {
                    $special_link = $_POST['special_link'];
                    foreach ($special_link as $i => $row) {
                        if (isset($row['speakers'])) {
                            foreach ($row['speakers'] as $j => $speaker) {
                                if (isset($speaker['image'])) {
                                    $filename = $this->base64ImageSaver($speaker['image'], APPPATH . "../themes/uploads/speaker/", $i . $j . time());
                                    if ($filename !== false) {
                                        $special_link[$i]['speakers'][$j]['image'] = base_url('themes/uploads/speaker/' . $filename);
                                    }
                                }
                            }
                            // unset($special_link[$i]['speakers']);
                        }
                    }
                }
                $event->special_link = (count($special_link) > 0 ? json_encode($special_link) : "[]");
                $event->save(false);
                //                $this->Event_m->insert($event, false);
                if (!$event_id) {
                    $event_id = $this->Event_m->getLastInsertID();
                }
                // if($this->input->post("special_link")){
                //     foreach($_POST['special_link'] as $i=>$row ){
                //         if(isset($row['speakers'])){
                //             file_put_contents(APPPATH."../themes/uploads/speaker/".$event_id.$i.".json",json_encode($row['speakers']));
                //         }else{
                //             if(file_exists(APPPATH."../themes/uploads/speaker/".$event_id.$i.".json"))
                //                 rename(APPPATH."../themes/uploads/speaker/".$event_id.$i.".json",APPPATH."../themes/uploads/speaker/".$event->id.$i."_del.json");
                //         }
                //     }
                // }

                $pricing = $this->Event_pricing_m->parseForm($data, $event_id);
                foreach ($pricing as $row) {
                    if (isset($row['id'])) {
                        $event_pricing = Event_pricing_m::findOne(['id' => $row['id']]);
                        if (!$event_pricing)
                            $event_pricing = new Event_pricing_m();
                    } else {
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

    public function delete_pricing()
    {
        if (!$this->input->method() == "post")
            show_404();
        $prices = $this->input->post('price');
        $ids = [];
        foreach ($prices as $row) {
            $ids[] = $row['id'];
        }
        $this->load->model(['Event_pricing_m', 'Transaction_detail_m']);
        $c = $this->Transaction_detail_m->find()->select("count(*) as c")->where_in("event_pricing_id", $ids)->get()->row_array();
        if ($c['c'] > 0) {
            $response['status'] = false;
            $response['message'] = "Cannot delete this pricing, The Pricing has been added in participant transaction !";
        } else {
            $status = $this->Event_pricing_m->find()->where_in("id", $ids)->delete();
            $response['status'] = $status;
            if ($status == false) {
                $response['message'] = "Failed to delete, error in server !";
            }
        }

        $this->output->set_content_type("application/json")
            ->_display(json_encode($response));
    }

    public function list_event()
    {
        $this->load->model('Event_m');
        $this->output->set_content_type("application/json")
            ->_display(json_encode(['data' => $this->Event_m->find()->get()->result_array()]));
    }
}
