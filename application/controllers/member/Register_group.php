<?php

/**
 * @property Event_m $Event_m
 * @property CI_Session $session
 * @property MY_Form_validation $form_validation
 * @property Transaction_detail_m $Transaction_detail_m
 */
class Register_group extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->theme = $this->config->item("theme");
        $this->layout->setLayout("layouts/$this->theme");
        $this->load->model(['Event_m']);
    }

    public function map_method($method = null, ...$params)
    {
        if (is_callable(['Register_group', $method])) {
            call_user_func_array([$this, $method], $params);
        } else {
            show_404();
        }
    }

    public function delete_cart()
    {
        $model = $this->session->userdata("group_model");
        $member_id = $this->input->post("member_id");
        $event_pricing_id = $this->input->post("event_pricing_id");
        $message = "";
        if ($model['transactions'][$member_id]) {
            $status = true;
            foreach ($model['transactions'][$member_id] as $index => $row) {
                if ($row['id'] == $event_pricing_id) {
                    $is_event_required = false;
                    foreach ($model['transactions'][$member_id] as $rowEvent) {
                        if ($rowEvent['event_required_id'] == $row['event_id']) {
                            $is_event_required = true;
                        }
                    }
                    if ($is_event_required == false) {
                        array_splice($model['transactions'][$member_id], $index, 1);
                    } else {
                        $status = false;
                        $message = "Event tidak bisa dihapus, karena merupakan event wajib";
                    }
                }
            }
        }
        $this->session->set_userdata("group_model", $model);
        $this->output->set_content_type("application/json")
            ->_display(json_encode([
                'status' => $status,
                'transactions' => $model['transactions'][$member_id] ?? [],
                'message' => $message,
            ]));
    }

    public function add_cart($memberId, $statusId)
    {
        $data = $this->input->post();
        $model = $this->session->userdata("group_model");
        if (!isset($data['is_hotel'])) {
            $this->load->model(['Transaction_detail_m', 'Event_pricing_m', 'Category_member_m']);
            $status = $this->Category_member_m->find()->where("id", $statusId)->get()->row();
            $addEventStatus = $this->Transaction_detail_m->validateAddEvent($data['id'], $memberId, $status->kategory, $model['transactions'][$memberId] ?? []);
            if ($addEventStatus === true) {
                // NOTE Harga sesuai dengan database
                $price = $this->Event_pricing_m->findOne(['id' => $data['id'], 'condition' => $status->kategory]);
                if ($price->price != 0) {
                    $data['price'] = $price->price;
                } else {
                    $kurs_usd = json_decode(Settings_m::getSetting('kurs_usd'), true);
                    $data['price'] = ($price->price_in_usd * $kurs_usd['value']);
                }
                $data['added'] = 1;
                $model['transactions'][$memberId][] = $data;
                $this->session->set_userdata("group_model", $model);
                $response['status'] = true;
            } else {
                $response['status'] = false;
                $response['message'] = $addEventStatus ?? "You are prohibited from following !";
            }
        } else {
            $result = $this->Transaction_detail_m->bookHotel($transaction->id, $this->session->userdata('tempMemberId'), $data);
            $data['price'] = 0;
            if ($result === true) {
                $data['price'] = 1;
                $response['status'] = true;
            } else {
                $response['status'] = false;
                $response['message'] = $result;
            }
        }
        $response['data'] = $data;
        $this->output->set_content_type("application/json")
            ->_display(json_encode($response));
    }

    public function index($id_invoice = null)
    {
        $this->load->model('Category_member_m');
        $this->load->model('Univ_m');
        $this->load->model('Country_m');

        $status = $this->Category_member_m->find()->select("id,kategory,need_verify")->where('is_hide', '0')->get()->result_array();
        $univ = $this->Univ_m->find()->select("univ_id, univ_nama")->order_by('univ_id')->get()->result_array();
        $participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory', 'Please Select your status');
        $this->load->model(['Member_m', 'User_account_m', 'Notification_m', 'Transaction_m', 'Transaction_detail_m']);

        $this->load->helper("form");
        $participantsCategory = Category_member_m::asList($status, 'id', 'kategory', 'Please Select your status');
        $data = [
            'participantsCategory' => $participantsCategory,
            'statusList' => $status,
            'univlist' => $univ,
            'events' => $this->Event_m->eventAvailableNow(),
            'paymentMethod' => Settings_m::getEnablePayment(),
            'model' => $this->session->userdata("group_model")
        ];
        $this->layout->render('member/' . $this->theme . '/register_group', $data);
    }

    public function add_members()
    {
        $postData = $this->input->post();
        $members = $postData['members'] ?? [];
        $this->load->model("Member_m");
        $this->load->library("form_validation");
        $this->form_validation->set_rules([
            ['field' => 'bill_to', 'label' => 'Bill to', 'rules' => 'required'],
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required'],
        ]);
        $status = $this->form_validation->run();
        $errorValidation = $this->form_validation->error_array();
        $memberCheck = is_array($members) && !empty($members);
        $membersError = $memberCheck ? "" : "No data members added";

        if ($status && $memberCheck) {
            foreach ($members as $index => $row) {
                $row['password'] = "tempPassword123";
                $row['confirm_password'] = "tempPassword123";
                $row['country'] = "1";
                $row['birthday'] = "2020-01-01";

                $this->form_validation->reset_validation();
                $this->form_validation->set_rules($this->Member_m->rules());
                $this->form_validation->set_data($row);
                if ($this->form_validation->run() == false) {
                    $memberCheck = false;
                    $members[$index]['validation'] = $this->form_validation->error_array();
                } else {
                    $members[$index]['validation'] = ['nik' => ''];
                }
            }
            if ($memberCheck) {
                $postData['members'] = $members;
                $this->session->set_userdata("group_model", $postData);
            }
        }
        $this->output->set_content_type("application/json")
            ->_display(json_encode([
                'status' => $status && $memberCheck,
                'message' => array_merge($errorValidation, ['members' => $membersError]),
                'members' => $members,
            ]));
    }

    public function get_events()
    {
        ini_set('memory_limit', '2048M');
        if ($this->input->method() !== 'post')
            show_404("Page not found !");
        $this->load->model(["Event_m", "Room_m", "Category_member_m"]);
        $statusId = $this->input->post("statusId");
        $memberId = $this->input->post("memberId");
        $status = $this->Category_member_m->find()->where("id", $statusId)->get()->row();
        $model = $this->session->userdata("group_model");
        $events = $this->Event_m->eventVueModel("No-ID", $status->kategory, [], false, $model['transactions'][$memberId] ?? []);
        $booking = $this->Room_m->bookedRoom("No-ID");
        $rangeBooking = $this->Room_m->rangeBooking();
        $this->output->set_content_type("application/json")
            ->_display(json_encode(['status' => true, 'events' => $events, 'booking' => $booking, 'rangeBooking' => $rangeBooking]));
    }
}
