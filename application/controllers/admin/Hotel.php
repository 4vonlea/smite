<?php


class Hotel extends Admin_Controller
{

	protected $accessRule = [
		'index' => 'view',
		'save' => 'insert',
		'delete' => 'delete',
		'grid' => 'view',
	];

	public function index()
	{
		$this->layout->render("hotel");
	}

	public function grid()
	{
		$this->load->model("Hotel_m");
		$grid = $this->Hotel_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}

	public function save()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(['Hotel_m', 'Room_m']);
		$this->load->library("form_validation");
		$rules = [
			['field' => 'name', 'label' => 'Hotel Name', 'rules' => 'required'],
			['field' => 'address', 'label' => 'Hotel Name', 'rules' => 'required'],
		];
		foreach ($this->input->post("rooms") ?? [] as $ind => $room) {
			$rules[] = ['field' => "rooms[$ind][name]", 'label' => 'Room Name', 'rules' => 'required'];
			$rules[] = ['field' => "rooms[$ind][quota]", 'label' => 'Quota', 'rules' => 'required|numeric'];
			$rules[] = ['field' => "rooms[$ind][range_date]", 'label' => 'Date of Available to Book', 'rules' => 'required'];
			$rules[] = ['field' => "rooms[$ind][price]", 'label' => 'Price', 'rules' => 'required|numeric'];
		}
		$this->form_validation->set_rules($rules);
		$data = [];
		$validation = [];
		if ($this->form_validation->run()) {
			$id = $this->input->post('id');
			if ($id) {
				$hotel = $this->Hotel_m->findOne($id);
			} else {
				$hotel = new Hotel_m();
			}

			$this->Hotel_m->getDB()->trans_start();
			$hotel->setAttributes($this->input->post());
			$status = $hotel->save();

			$data = $hotel->toArray();
			foreach ($this->input->post("rooms") as $room) {
				$roomData = [
					'name' => $room['name'],
					'hotel_id' => $hotel->id,
					'description' => $room['description'] ?? "",
					'quota' => $room['quota'] ?? "0",
					'price' => $room['price'] ?? "0",
					'start_date' => DateTime::createFromFormat("d M Y", $room['range_date'][0])->format("Y-m-d"),
					'end_date' => DateTime::createFromFormat("d M Y", $room['range_date'][1])->format("Y-m-d"),
				];
				if (isset($room['id'])) {
					$this->Room_m->update($roomData, $room['id']);
					$roomData['id'] = $room['id'];
				} else {
					$this->Room_m->insert($roomData);
					$roomData['id'] = $this->Room_m->getLastInsertID();
				}
				$roomData['range_date'] = [$room['range_date'][0], $room['range_date'][1]];
				$data['rooms'][] = $roomData;
			}
			$this->Hotel_m->getDB()->trans_complete();
			$status = $this->Hotel_m->getDB()->trans_status();
		} else {
			$status = false;
			$validation = $this->form_validation->error_array();
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'data' => $data, 'validation' => $validation]));
	}

	public function delete_room()
	{

		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$message = "";
		$this->load->model(["Room_m", "Transaction_m","Transaction_detail_m"]);
		$post = $this->input->post();

		$this->Transaction_detail_m->find()->join('transaction','transaction.id = transaction_id')
		->where("status_payment !=",Transaction_m::STATUS_EXPIRE)
		->where("room_id", $this->input->post('id'));
		if ($this->Transaction_detail_m->count() == 0) {
			$status = $this->Room_m->find()->where('id', $post['id'])->delete();
			if ($status == false)
				$message = "Failed to delete room, error on server !";
		} else {
			$status = false;
			$message = "Room data cannot be deleted, because it has been used on a transaction";
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}

	public function delete()
	{

		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$message = "";
		$this->load->model(["Hotel_m", "Room_m"]);
		$post = $this->input->post();

		$this->Room_m->find()->where("hotel_id", $this->input->post('id'));
		if ($this->Room_m->count() == 0) {
			$status = $this->Hotel_m->find()->where('id', $post['id'])->delete();

			if ($status == false)
				$message = "Failed to delete member, error on server !";
		} else {
			$status = false;
			$message = "Hotel cannot be deleted, because it still has room data";
		}

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}

	public function detail()
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id');
			$this->load->model(['Hotel_m']);
			$data = $this->Hotel_m->withRooms($id);
			$this->output
				->set_content_type("application/json")
				->_display(json_encode($data));
		} else {
			$this->output->set_status_header(403);
		}
	}
}
