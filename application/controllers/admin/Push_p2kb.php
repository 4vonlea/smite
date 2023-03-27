<?php

/**
 * Class Event
 * @property Event_m $Event_m
 */
class Push_p2kb extends Admin_Controller
{
    protected $accessRule = [
        'index' => 'view',
        'grid' => 'view',
        'delete' => 'delete',
        'detail' => 'view',
        'save' => 'insert',
    ];

    public function index()
    {
      
        $this->layout->render("push_p2kb", []);
    }

    public function grid()
    {
        $this->load->model(['Event_m','Papers_m']);

        $countParticipantQuery = "SELECT COUNT(td.id) AS jumlahParticipant, ep.event_id FROM transaction_details td
		JOIN `transaction` tr ON tr.id = td.transaction_id
		JOIN event_pricing ep ON ep.id = td.event_pricing_id
		WHERE tr.status_payment = 'settlement'
		GROUP BY ep.event_id";

        $data = $this->Event_m->find()->join("({$countParticipantQuery}) as countParticipant",'countParticipant.event_id = events.id','left')
            ->select("id,name,kategory as category,jumlahParticipant as participant")->get()->result_array();

        $data[] = [
            'id'=>'paper',
            'name'=>'Paper/Manuscript Submission',
            'category'=>'Paper',
            'participant'=>$this->Papers_m->certificateReceiverQuery("Peserta")->count_all_results()
        ];
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode([
                'status'=>true,
                'data'=>$data
            ]));
    }


    public function jenis_aktivitas($aktivitasCode)
    {
        $this->load->library("Api_perdossi");
        $responseApi = $this->api_perdossi->jenisAktivitas($aktivitasCode);
        $data = [];
        if ($responseApi['message'] ?? "" == "success") {
            $data = $responseApi['jenisaktivitas'];
        }
        $this->output->set_content_type("application/json")
            ->_display(json_encode([
                'data' => $data
            ]));
    }

    public function skp($roleCode)
    {
        $this->load->library("Api_perdossi");
        $responseApi = $this->api_perdossi->skp($roleCode);
        $data = [];
        if ($responseApi['message'] ?? "" == "success") {
            $data = $responseApi['skp'];
        }
        $this->output->set_content_type("application/json")
            ->_display(json_encode([
                'data' => $data
            ]));
    }

    public function map($id)
    {
        $this->load->model(['Event_m', 'Event_pricing_m','Papers_m']);

        if ($this->input->post()) {
            $map = $this->input->post("map");
            if($id =='paper'){
                $status = $this->Papers_m->saveMapP2KB($map);
            }else{
                $status = $this->Event_m->saveMap($id, $map);
            }
            $this->output->set_content_type("application/json")
                ->_display(json_encode([
                    'status' => $status
                ]));
        } else {
            $this->load->library("Api_perdossi");
            $responseApi = $this->api_perdossi->listAktivitas();
            $listAktivitas = [];
            if ($responseApi['message'] ?? "" == "success") {
                $listAktivitas = $responseApi['aktivitas'];
            }
            if($id == 'paper'){
                $data = $this->Papers_m->getMapP2KB();
            }else{
                $data = $this->Event_m->getMapping($id);
            }
            $this->output->set_content_type("application/json")
                ->_display(json_encode([
                    'data' =>$data,
                    'aktivitas' => $listAktivitas,
                ]));
        }
    }

    public function push_participant()
    {
        $this->load->model(['Event_m','Papers_m']);
        $type = $this->input->post("type");
        $eventId = $this->input->post("event_id");
        if($eventId == "paper"){
            $query = $this->Papers_m->certificateReceiverQuery();
        }else{
            $query = $this->Event_m->getParticipant("td.id as id,m.fullname,m.nik,ep.condition as status_member,m.p2kb_member_id")
                ->where("t.id", $eventId);
        }

        // $query->limit(5);
        $status = true;
        $message = "";
        $data = [];

        if (file_exists(APPPATH . "uploads/cert_template/$eventId.txt")) {
            if ($type == 'notyet')
                $query->where("NULLIF(p2kb_push,' ') IS NULL");
            $data = $query->get()->result_array();
        } else {
            $status = false;
            $message = "Certificate template is not found, Please set template on setting menu";
        }
        $this->output->set_content_type("application/json")
            ->_display(json_encode([
                'data' => $data,
                'status' => $status,
                'message' => $message,
            ]));
    }

    public function push_data()
    {
        $this->load->model(['Event_m','Transaction_detail_m','Papers_m']);
        $this->load->library("Api_perdossi");

        $eventId = $this->input->post("event_id");
        $participant = $this->input->post("participant");
        if($eventId == "paper"){
            $data = $this->Papers_m->getDataPushP2KB($participant['id']);
        }else{
            $data = $this->Event_m->getDataPushP2KB($participant['id']);
        }
        if(is_array($data)){
            $response = $this->api_perdossi->insertKegiatan($data);
            if($response['status'] && $response['statusResponse'] == 'Berhasil'){
                if($eventId == "paper"){
                    $data = $this->Papers_m->updateDataPushP2KB($participant['id'],$response);
                }else{
                    $data = $this->Transaction_detail_m->update(['p2kb_push'=>json_encode($response)],['id'=>$participant['id']],false);
                }
            }
        }else{
            $response['status'] = false;
            $response['message'] = $data;
            $response['statusResponse'] = "Gagal";
        }
        $this->output->set_content_type("application/json")
            ->_display(json_encode($response));
    }

}
