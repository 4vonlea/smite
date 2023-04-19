<?php

/**
 * @property Event_m $Event_m
 * @property CI_Session $session
 * @property MY_Form_validation $form_validation
 * @property Transaction_detail_m $Transaction_detail_m
 * @property Member_m $Member_m
 * @property User_account_m $User_account_m
 * @property Category_member_m $Category_member_m
 * @property Univ_m $Univ_m
 * @property Notification_m $Notification_m
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
        if ($this->input->method() !== 'post')
            show_404("Page not found !");
        $this->load->model(["Transaction_detail_m"]);
        $member_id = $this->input->post("member_id");
        $transaction_detail_id = $this->input->post("transaction_detail_id");
        $response = $this->Transaction_detail_m->deleteItem($transaction_detail_id);
        $model = $this->session_model();
        $response['transactionsMember'] = $model['transactions'][$member_id];
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
        $model = $this->session_model($id_invoice);

        $data = [
            'participantsCategory' => $participantsCategory,
            'statusList' => $status,
            'univlist' => $univ,
            'events' => $this->Event_m->eventAvailableNow(),
            'paymentMethod' => Settings_m::getEnablePayment(),
            'model' => $model
        ];
        $this->layout->render('member/' . $this->theme . '/register_group', $data);
    }

    public function add_members()
    {
        $postData = $this->input->post();
        $members = $postData['members'] ?? [];
        $this->load->model(["Member_m", "Notification_m", "Category_member_m", "User_account_m", "Univ_m"]);
        $this->load->library("form_validation");
        $this->load->library('Uuid');
        $this->form_validation->set_rules([
            ['field' => 'bill_to', 'label' => 'Bill to', 'rules' => 'required'],
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required'],
        ]);
        $status = $this->form_validation->run();
        $errorValidation = $this->form_validation->error_array();
        $memberCheck = is_array($members) && !empty($members);
        $membersError = $memberCheck ? "" : "No data members added";
        // $statusList = $this->Category_member_m->find()->select("id,kategory,need_verify")->where('is_hide', '0')->get()->result_array();

        if ($status && $memberCheck) {
            foreach ($members as $index => $row) {
                $row['password'] = "a1s2d3";
                $row['confirm_password'] = "a1s2d3";
                $row['country'] = "1";
                $row['birthday'] = "2020-01-01";
                $row['phone'] = "0";

                $dataMember = $this->Member_m->findOne(['id' => $row['id'] ?? "-"]);
                $rules = $this->Member_m->rules(
                    $dataMember &&
                        $dataMember->nik == $row['nik'] && $dataMember->email == $row['email']
                );

                $this->form_validation->reset_validation();
                $this->form_validation->set_rules($rules);
                $this->form_validation->set_data($row);
                if ($this->form_validation->run() == false) {
                    $memberCheck = false;
                    $members[$index]['validation'] = $this->form_validation->error_array();
                } else {
                    $id = Uuid::v4();
                    $this->Member_m->getDB()->trans_start();
                    $need_verify = (isset($status[$row['status']]) && $status[$row['status']]['need_verify'] == "1");
                    $row['username_account'] = $row['email'];
                    $row['verified_by_admin'] = !$need_verify;
                    $row['verified_email'] = 0;
                    $row['region'] = 0;
                    if ($row['univ'] == Univ_m::UNIV_OTHER) {
                        $this->Univ_m->insert(['univ_nama' => strtoupper($row['other_institution'])]);
                        $row['univ'] = $this->Univ_m->last_insert_id;
                    }
                    if ($dataMember) {
                        $id = $dataMember->id;
                        $row['id'] = $id;
                        $dataMember = $dataMember->toArray();
                        foreach ($dataMember as $key => $value) {
                            if (isset($row[$key])) {
                                $dataMember[$key] = $row[$key];
                            }
                        }
                        $this->Member_m->update($dataMember, $id, false);
                        $this->User_account_m->update([
                            'username' => $row['email'],
                        ], $dataMember['email'], false);
                    } else {
                        $token = uniqid();
                        $row['id'] = $id;
                        $this->Member_m->insert(array_intersect_key($row, array_flip($this->Member_m->fillable)), false);
                        $this->User_account_m->insert([
                            'username' => $row['email'],
                            'password' => password_hash($row['password'], PASSWORD_DEFAULT),
                            'role' => 0,
                            'token_reset' => "verifyemail_" . $token
                        ], false);
                    }
                    $this->Member_m->getDB()->trans_complete();
                    $statusAddMember = $this->Member_m->getDB()->trans_status();
                    if ($statusAddMember && isset($token)) {
                        $this->Notification_m->sendEmailConfirmation($row, $token);
                        $this->Notification_m->setType(Notification_m::TYPE_WA)->sendEmailConfirmation($row, $token);
                    }
                    unset($row['password'], $row['confirm_password']);
                    $members[$index] = $row;
                    $members[$index]['validation'] = ['nik' => ''];
                }
            }
            $postData['members'] = $members;
            $this->session->set_userdata("group_model", $postData);
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
        $events = $this->Event_m->eventVueModel($memberId, $status->kategory, [], false);
        $booking = $this->Room_m->bookedRoom($memberId);
        $rangeBooking = $this->Room_m->rangeBooking();
        $this->output->set_content_type("application/json")
            ->_display(json_encode(['status' => true, 'events' => $events, 'booking' => $booking, 'rangeBooking' => $rangeBooking]));
    }

    public function create_transaction()
    {
        $this->load->model(["Transaction_m", "Transaction_detail_m"]);
        $model = $this->session_model();
        $fee = $this->Transaction_detail_m->findOne(['transaction_id' => $model['transactions']['invoice_id'], 'event_pricing_id' => 0]);
        if (!$fee) {
            $fee = new Transaction_detail_m();
            $fee->event_pricing_id = 0; //$data['id'];
            $fee->transaction_id = $model['transactions']['invoice_id'];
            $fee->price = Transaction_m::ADMIN_FEE_START + rand(100, 500); //"6000";//$data['price'];
            $fee->member_id = "REGISTER-GROUP : " . $model['bill_to'];
            $fee->product_name = "Admin Fee";
            $fee->save();
        }
        $model = $this->session_model();

        $this->output->set_content_type("application/json")
            ->_display(json_encode(['status' => true, 'transactions' => $model['transactions']]));
    }

    public function add_cart($memberId, $statusId)
    {
        $data = $this->input->post();
        $model = $this->session_model();
        $this->load->model(["Transaction_m", "Transaction_detail_m", "Event_m", "Event_pricing_m", "Category_member_m"]);

        if (!isset($model['transactions']['invoice_id']) || $model['transactions']['invoice_id'] == "") {
            $invoice_id = $this->Transaction_m->generateInvoiceID();
            $transaction = new Transaction_m();
            $transaction->id = $invoice_id;
            $transaction->checkout = 0;
            $transaction->status_payment = Transaction_m::STATUS_WAITING;
            $transaction->email_group = $model['email'];
            $transaction->member_id = "REGISTER-GROUP : " . $model['bill_to'];
            $transaction->save();
            $model['transactions']['invoice_id'] = $invoice_id;
            $this->session->set_userdata("group_model", $model);
        }
        $response['invoice_id'] = $model['transactions']['invoice_id'];

        $detail = $this->Transaction_detail_m->findOne(['transaction_id' => $model['transactions']['invoice_id'], 'event_pricing_id' => $data['id']]);
        if (!$detail) {
            $detail = new Transaction_detail_m();
        }
        $status = $this->Category_member_m->find()->where("id", $statusId)->get()->row();

        if (!isset($data['is_hotel'])) {
            $addEventStatus = $this->Transaction_detail_m->validateAddEvent($data['id'], $memberId, $status->kategory);
            if ($addEventStatus === true) {
                $price = $this->Event_pricing_m->findOne(['id' => $data['id'], 'condition' => $status->kategory]);
                if ($price->price != 0) {
                    $data['price'] = $price->price;
                } else {
                    $kurs_usd = json_decode(Settings_m::getSetting('kurs_usd'), true);
                    $data['price'] = ($price->price_in_usd * $kurs_usd['value']);
                }
                $detail->event_pricing_id = $data['id'];
                $detail->transaction_id = $model['transactions']['invoice_id'];
                $detail->price = $data['price'];
                $detail->price_usd = $price->price_in_usd;
                $detail->member_id = $memberId;
                $detail->product_name = "$data[event_name] ($data[member_status])";
                $detail->save();
                $model['transactions'][$memberId][] = $detail->toArray();
                $response['status'] = true;
                $this->Transaction_m->setDiscount($model['transactions']['invoice_id'], $memberId);
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
        $model = $this->session_model();
        $response['transactionsMember'] = $model['transactions'][$memberId] ?? [];
        $this->output->set_content_type("application/json")
            ->_display(json_encode($response));
    }


    protected function session_model($invoice_id = null)
    {
        $model = $this->session->userdata("group_model");
        $this->load->model("Transaction_m");
        $transaction = $this->Transaction_m->find()
            ->like("member_id", "REGISTER-GROUP", "after")
            ->where("id", $invoice_id ?? $model['transactions']['invoice_id'] ?? "")->get()->row();
        if ($model == null || !$transaction || $transaction->status_payment != Transaction_m::STATUS_WAITING) {
            $model = [
                'bill_to' => '',
                'email' => '',
                'members' => [],
                'transactions' => [
                    'invoice_id' => ''
                ]
            ];
            $this->session->set_userdata("group_model", $model);
        } else if ($invoice_id && $transaction) {
            $members = $this->Transaction_detail_m->find()->select("m.*, '' as validation")
                ->join("members m", "m.id = member_id")
                ->where("transaction_id", $transaction->id)
                ->group_by("member_id")
                ->get()->result_array();
            $model = [
                'bill_to' => str_replace("REGISTER-GROUP : ", "", $transaction->member_id),
                'email' => $transaction->email_group,
                'members' => $members,
                'transactions' => [
                    'invoice_id' => $transaction->id
                ]
            ];
        }
        if (isset($model['transactions']['invoice_id']) && $model['transactions']['invoice_id'] != "") {
            $tempTransaction = [
                'invoice_id' => $model['transactions']['invoice_id']
            ];
            $this->load->model("Transaction_detail_m");
            $transactionDetail = $this->Transaction_detail_m->find()->where('transaction_id', $model['transactions']['invoice_id'])->get();
            foreach ($transactionDetail->result_array() as $row) {
                $tempTransaction[$row['member_id']][] = $row;
            }
            $model['transactions'] = $tempTransaction;
        }
        return $model;
    }
}
