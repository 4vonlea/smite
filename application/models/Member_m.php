<?php


class Member_m extends MY_Model
{
	protected $table = "members";

	public $fillable = ['id', 'image', 'email', 'fullname', 'gender', 'phone', 'birthday', 'country', 'region', 'city', 'address', 'username_account', 'status', 'verified_by_admin', 'verified_email',];

	public static $proofExtension = "jpg|png|jpeg";

	public function rules()
	{
		return [
			['field' => 'email', 'rules' => 'required|max_length[100]|valid_email'],
			['field' => 'password', 'rules' => 'required|max_length[100]'],
			['field' => 'confirm_password', 'rules' => 'required|matches[password]'],
			['field' => 'status', 'rules' => 'required'],
			['field' => 'fullname', 'rules' => 'required|max_length[100]'],
			['field' => 'address', 'rules' => 'required'],
			['field' => 'city', 'rules' => 'required'],
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
}
