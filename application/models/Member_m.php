<?php


class Member_m extends MY_Model
{
	protected $table = "members";

	public $fillable = ['id', 'image', 'email', 'fullname', 'gender', 'phone', 'birthday', 'country', 'region', 'city', 'univ', 'address', 'username_account', 'status', 'verified_by_admin', 'verified_email'];

	public static $proofExtension = "jpg|png|jpeg";

	public function rules($insert = false)
	{
		return [
			[
				'field' => 'email', 'rules' => 'required|max_length[100]|valid_email|is_unique[members.email]',
				'errors'=>['is_unique'=>'This email already exist !']
			],
			['field' => 'password', 'rules' => 'required|max_length[100]'],
			['field' => 'confirm_password', 'rules' => 'required|matches[password]'],
			['field' => 'status', 'rules' => 'required'],
			['field' => 'fullname', 'rules' => 'required|max_length[100]'],
//			['field' => 'address', 'rules' => 'required'],
//			['field' => 'city', 'rules' => 'required'],
			['field' => 'univ', 'rules' => 'required'],
			['field' => 'phone', 'rules' => 'required|numeric'],
			['field' => 'birthday', 'rules' => 'required'],
		];
	}

	public function getImageLink()
	{
		if ($this->image)
			return base_url("themes/uploads/profile/$this->image");
		return base_url('themes/uploads/people.jpg');
	}

	public function status_member()
	{
		return $this->hasOne("Category_member_m", "id", "status");
	}

	public function gridData($params, $relationship = [])
	{
		$data = parent::gridData($params, $relationship);
		$data['total_unverified'] = $this->find()->where('verified_by_admin', 0)->count_all_results();
		$data['total_members'] = $this->find()->count_all_results();
		return $data;
	}

	/**
	 * @param $event
	 * @param array $member
	 * @return \Dompdf\Dompdf
	 */
	public function getCard($event, $member = array())
	{
		require_once APPPATH . "third_party/phpqrcode/qrlib.php";

		if (count($member) == 0) {
			$member = $this->toArray();
		}
		if (!is_array($event)) {
			$this->load->model("Event_m");
			$event = $this->Event_m->findOne($event)->toArray();
		}

		$html = $this->load->view("template/member_card", ['event' => $event, 'member' => $member], true);
		$dompdf = new Dompdf\Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->render();
		$dompdf->setPaper("A5", "portrait");
		return $dompdf;
	}

	/**
	 * @param null $id
	 * @return int
	 */
	public function countFollowedEvent($id = null){
		if($id == null)
			$id = $this->id;
		$this->load->model("Transaction_m");
		return $this->setAlias("t")->find()->select("count(*) as ev")
			->join("transaction tr","tr.member_id = t.id")
			->join("transaction_details td","td.transaction_id = tr.id")
			->where("event_pricing_id > 0")
			->where("t.id",$id)
			->where("tr.status_payment",Transaction_m::STATUS_FINISH)
			->get()->row()->ev;
	}
}
