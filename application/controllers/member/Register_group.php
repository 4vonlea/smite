<?php

/**
 * @property Event_m $Event_m
 * @property CI_Session $session
 * @property MY_Form_validation $form_validation
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

    public function index($id_invoice = null)
    {
        $this->load->model('Category_member_m');
        $this->load->model('Univ_m');
        $this->load->model('Country_m');

        $status = $this->Category_member_m->find()->select("id,kategory,need_verify")->where('is_hide', '0')->get()->result_array();
        $univ = $this->Univ_m->find()->select("univ_id, univ_nama")->order_by('univ_id')->get()->result_array();
        $participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory', 'Please Select your status');
        $this->load->model(['Member_m', 'User_account_m', 'Notification_m', 'Transaction_m', 'Transaction_detail_m']);

        if ($this->input->post()) {

            $eventAdded = json_decode($this->input->post('eventAdded'));

            // NOTE Validasi Event Required
            $isRequired = 0;
            foreach ($eventAdded as $key => $value) {
                if (isset($value->event_required) && $value->event_required != '') {
                    $findRequired = array_search($value->event_required, array_column($eventAdded, 'event_name'));
                    if ($findRequired === false) {
                        $isRequired += 1;
                    }
                }
            }

            $this->load->model(['Member_m', 'User_account_m', 'Notification_m', 'Transaction_m', 'Transaction_detail_m']);
            $this->load->library('form_validation');
            $this->load->library('Uuid');

            $data = $this->input->post();
            unset($data['eventAdded']);

            $data['id'] = Uuid::v4();
            $univ = Univ_m::withKey($univ, "univ_id");
            $status = Category_member_m::withKey($status, "id");
            $need_verify = (isset($status[$data['status']]) && $status[$data['status']]['need_verify'] == "1");

            // NOTE Data Members
            $members = json_decode($data['members'], true);

            // NOTE Data Sebelumnya
            $dataSebelumnya = json_decode($data['data'], true);

            // NOTE Delete Member
            if (isset($dataSebelumnya['members'])) {
                foreach ($dataSebelumnya['members'] as $key => $value) {
                    $find = array_search($value['email'], array_column($members, 'email'));
                    if ($find === false) {
                        $this->Member_m->delete(['email' => $value['email']]);
                        $this->User_account_m->delete($value['email']);
                    }
                }
            }

            // NOTE Hapus Transactions Detail jika sudah dikirim id_invoice
            if (isset($dataSebelumnya['id_invoice']) && $dataSebelumnya['id_invoice'] != '') {
                $this->Transaction_detail_m->delete(['transaction_id' => $dataSebelumnya['id_invoice']]);
            }

            $id_invoice = (isset($dataSebelumnya['id_invoice']) && $dataSebelumnya['id_invoice'] != '') ? $dataSebelumnya['id_invoice'] : $this->Transaction_m->generateInvoiceId();

            $statusParticipant = $data['status'];
            $bill_to = "REGISTER-GROUP : {$data['bill_to']}";
            $bill_to_input = $data['bill_to'];
            $email_group = $data['email_group'];

            $this->form_validation->set_rules('bill_to', 'Bill To', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            $this->form_validation->set_rules('email_group', 'Email', 'required|valid_email');
            $validationError = false;
            $model['validation_error'] = [];
            // NOTE Validasi Bill To dan Status
            if (!$this->form_validation->run()) {
                $validationError = true;
                $model['validation_error']['bill_to'] = form_error('bill_to');
                $model['validation_error']['status'] = form_error('status');
                $model['validation_error']['email_group'] = form_error('email_group');
            }

            // NOTE Validasi Jumlah Member
            if (count($members) == 0) {
                $validationError = true;
                $model['validation_error']['members'] = 'Minimal 1 member';
            }

            // NOTE Validation Members
            $count = 0;
            foreach ($members as $key => $data) {
                $members[$key]['id_invoice'] = $id_invoice;
                $members[$key]['bill_to'] = $bill_to;
                $members[$key]['bill_to_input'] = $bill_to_input;
                $members[$key]['id'] = Uuid::v4();
                $members[$key]['password'] = isset($data['password']) ? $data['password'] : strtoupper(substr(uniqid(), -5));
                $members[$key]['confirm_password'] = $members[$key]['password'];
                $members[$key]['phone'] = $data['phone'] ?? "0";
                $members[$key]['nik'] = $data['nik'] ?? "0";
                $members[$key]['kta'] = $data['kta'] ?? "0";
                $members[$key]['region'] = '0';
                $members[$key]['country'] = '0';
                $members[$key]['birthday'] = date('Y-m-d');
                $members[$key]['sponsor'] = $bill_to_input;
                $members[$key]['status'] = $statusParticipant;
                if (!$this->Member_m->validate($members[$key]) || !$this->handlingProof('proof', $members[$key]['id'], $need_verify)) {
                    $error['statusData'] = false;
                    $members[$key]['validation_error'] = array_merge(
                        $this->Member_m->getErrors(),
                        [
                            'proof' => (isset($this->upload) ? $this->upload->display_errors("", "") : null),
                        ]
                    );
                    $count += 1;
                }
            }

            $error = [];
            if ($count > 0 || $validationError || $isRequired > 0 || count($eventAdded) == 0) {
                $status = false;
            } else {
                $status = true;

                // NOTE Insert atau Update Transaction
                $transaction = $this->Transaction_m->findOne(['id' => $id_invoice, 'checkout' => 0]);
                if (!$transaction) {
                    $id = $id_invoice;
                    $transaction = new Transaction_m();
                    $transaction->id = $id;
                    $transaction->checkout = 0;
                    $transaction->status_payment = Transaction_m::STATUS_WAITING;
                    $transaction->member_id = $bill_to;
                    $transaction->email_group = $email_group;
                    $transaction->save();
                    $transaction->id = $id;
                }

                $tr = $this->Transaction_m->findOne($id_invoice);
                foreach ($members as $key => $data) {
                    $data['username_account'] = $data['email'];
                    $data['verified_by_admin'] = !$need_verify;
                    $data['verified_email'] = 0;
                    $data['region'] = 0;
                    // $data['country'] = 0;
                    $data['isGroup'] = true;

                    $token = uniqid();
                    $this->Member_m->getDB()->trans_start();

                    // NOTE Institution Other
                    if ($data['univ'] == Univ_m::UNIV_OTHER) {
                        $_POST['univ'] = $data['univ'];
                        $this->Univ_m->insert(['univ_nama' => strtoupper($data['other_institution'])]);
                        $data['univ'] = $this->Univ_m->last_insert_id;
                    }
                    // NOTE Country Other
                    if ($data['country'] == Country_m::COUNTRY_OTHER) {
                        $_POST['country'] = $data['country'];
                        $this->Country_m->insert(['name' => strtoupper($data['other_country'])]);
                        $data['country'] = $this->Country_m->last_insert_id;
                    }
                    // NOTE Insert Member
                    $dataMember = $this->Member_m->findOne(['email' => $data['email']]);
                    if ($dataMember) {
                        $dataMember = $dataMember->toArray();
                        $dataMember['fullname'] = $data['fullname'];
                        $dataMember['phone'] = $data['phone'] ?? "0";
                        $dataMember['kta'] = $data['kta'] ?? "";
                        $dataMember['univ'] = $data['univ'];
                        $dataMember['sponsor'] = $data['sponsor'];
                        $this->Member_m->update($dataMember, $dataMember['id'], false);

                        $members[$key]['id'] = $data['id'] = $dataMember['id'];
                        // NOTE Accounts
                        $this->User_account_m->update([
                            'username' => $data['email'],
                            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                            'role' => 0,
                            'token_reset' => "verifyemail_" . $token
                        ], $data['email'], false);
                    } else {
                        $this->Member_m->insert(array_intersect_key($data, array_flip($this->Member_m->fillable)), false);

                        // NOTE Accounts
                        $this->User_account_m->insert([
                            'username' => $data['email'],
                            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                            'role' => 0,
                            'token_reset' => "verifyemail_" . $token
                        ], false);

                        $this->Notification_m->sendEmailConfirmation($data, $token);
                        $this->Notification_m->setType(Notification_m::TYPE_WA)->sendEmailConfirmation($data, $token);
                    }

                    /* -------------------------------------------------------------------------- */
                    /*                              NOTE Transactions                             */
                    /* -------------------------------------------------------------------------- */
                    $this->transactions($data, $eventAdded, $transaction);

                    $this->Member_m->getDB()->trans_complete();
                    $error['statusData'] = $this->Member_m->getDB()->trans_status();
                    $error['message'] = $this->Member_m->getDB()->error();

                    if ($error['statusData']) {
                        if ($data) {
                            $this->Notification_m->sendRegisteredByOther($data, $tr, $participantsCategory);
                            $this->Notification_m->setType(Notification_m::TYPE_WA)->sendRegisteredByOther($data, $tr, $participantsCategory);
                        }
                    }
                }
                $invoiceDataPdf = $tr->exportInvoice()->output();
                $this->Notification_m->sendMessageWithAttachment($tr->email_group, 'Your Group Registration Billing', "<p>Dear Participant.</p><p>You have succesfully group registered and we have created an invoice to pay off based on your event participation. Thank you and we are happy to meet you in this event(s).</p><p>Herewith we attached your invoice to pay off. Please complete your transaction as soon as possible. Our system will automatically settle after we receive your payment. If you dont receive any further notification after completing your transferred payment for more than 1x24 hours, please contact the committee.</p><p>Thank you.</p><p>Registration Committee</p>", [
                    'invoice.pdf' => $invoiceDataPdf,
                ]);
                $error['transactions'] = $this->getTransactions($transaction);
            }
            $this->output->set_content_type("application/json")
                ->set_output(json_encode(array_merge(
                    $error,
                    [
                        'status' => $status,
                        'data' => [
                            'bill_to' => $bill_to_input,
                            'id_invoice' => $id_invoice,
                            'email_group' => $email_group,
                            'members' => $members,
                            'validation_error' => array_merge($model['validation_error'], [
                                'eventAdded' => (count($eventAdded) == 0)  ? 'Choose at least 1 event' : '',
                                'requiredEvent' => $isRequired > 0 ? 'Please select required event' : '',
                            ]),
                        ]
                    ]
                )));
        } else {
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

            if ($id_invoice) {
                $transaction = $this->Transaction_m->findOne($id_invoice);
                if ($transaction->status_payment == Transaction_m::STATUS_WAITING) {
                    $error['transactions'] = $this->getTransactions($transaction);
                    $bill_to = $transaction->member_id;
                    $bill_to_input = str_replace("REGISTER-GROUP : ", "", $transaction->member_id);
                    $listMember = $this->Transaction_detail_m->find()->where("transaction_id", $id_invoice)
                        ->join("members", "members.id = transaction_details.member_id")
                        ->join("kategory_members km", "km.id = members.status")
                        ->select("members.*,km.kategory as status_text")
                        ->get()->result();
                    $members = [];
                    $status_selected = "";
                    $status_text = "";
                    foreach ($listMember as $key => $dataMember) {
                        $members[$key]['id_invoice'] = $id_invoice;
                        $members[$key]['bill_to'] = $bill_to;
                        $members[$key]['bill_to_input'] = $bill_to_input;
                        $members[$key]['id'] = $dataMember->id;
                        $members[$key]['phone'] = $dataMember->phone;
                        $members[$key]['region'] = $dataMember->region;
                        $members[$key]['country'] = $dataMember->country;
                        $members[$key]['birthday'] = $dataMember->birthday;
                        $members[$key]['sponsor'] = $bill_to_input;
                        $members[$key]['nik'] = $dataMember->nik;
                        $members[$key]['fullname'] = $dataMember->fullname;
                        $members[$key]['email'] = $dataMember->email;
                        $members[$key]['univ'] = $dataMember->univ;
                        $members[$key]['p2kb_member_id'] = $dataMember->p2kb_member_id;
                        $members[$key]['status'] = $dataMember->status;
                        $members[$key]['validation_error'] = ['nik' => null];
                        $members[$key]['checking'] = false;
                        $status_selected = $dataMember->status;
                        $status_text = $dataMember->status_text;
                    }
                    $data['continueTransaction'] = (array_merge(
                        $error,
                        [
                            'status' => ['status_selected' => $status_selected, 'status_text' => $status_text],
                            'data' => [
                                'bill_to' => $bill_to_input,
                                'id_invoice' => $id_invoice,
                                'email_group' => $transaction->email_group,
                                'members' => $members,
                            ]
                        ]
                    ));
                }
            }
            $this->layout->render('member/' . $this->theme . '/register_group', $data);
        }
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
}
