<?php


class Member_m extends MY_Model
{
	protected $table = "members";
	public $fillable = ['id', 'image', 'email', 'fullname','kta', 'gender', 'phone', 'birthday', 'country', 'region', 'city', 'univ', 'address', 'username_account', 'status', 'verified_by_admin', 'verified_email', 'sponsor', 'alternatif_status','alternatif_status2','nik'];

	public static $proofExtension = "jpg|png|jpeg";

	public function rules($insert = false)
	{
		$this->load->model("Univ_m");
		$validations = '|is_unique[members.email]';
		if (isset($_POST['group']) || isset($_POST['update'])) {
			$validations = '';
		}
		$rules = [
			[
				'field' => 'email', 'label' => 'Email', 'rules' => "required|max_length[100]|valid_email{$validations}",
				'errors' => ['is_unique' => 'This email already exist !']
			],
			['field' => 'password', 'label' => 'Password', 'rules' => 'required|max_length[100]'],
			['field' => 'confirm_password', 'label' => 'Confirm Password', 'rules' => 'required|matches[password]'],
			['field' => 'status', 'label' => 'Status', 'rules' => 'required'],
			['field' => 'fullname', 'label' => 'Full Name', 'rules' => 'required|max_length[100]'],
			//			['field' => 'address', 'rules' => 'required'],
			//			['field' => 'city', 'rules' => 'required'],
			['field' => 'univ', 'label' => 'Institution', 'rules' => 'required'],
			['field' => 'country', 'label' => 'Country', 'rules' => 'required'],
			['field' => 'nik', 'label' => 'NIK', 'rules' => 'required|exact_length[16]'],
			['field' => 'phone', 'label' => 'Phone/WA', 'rules' => 'numeric'],
			['field' => 'birthday', 'label' => 'Birthday', 'rules' => 'required'],
		];
		if (isset($_POST['univ']) && $_POST['univ'] == Univ_m::UNIV_OTHER) {
			$rules[] = ['field' => 'other_institution', 'label' => 'Other Institution', 'rules' => 'required'];
		}
		if (isset($_POST['haveSponsor']) && $_POST['haveSponsor'] == 1) {
			$rules[] = ['field' => 'sponsor', 'label' => 'Sponsor', 'rules' => 'required'];
		}
		if (isset($_POST['country']) && $_POST['country'] == Country_m::COUNTRY_OTHER) {
			$rules[] = ['field' => 'other_country', 'label' => 'Other Country', 'rules' => 'required'];
		}

		// if (isset($_POST['selectedPaymentMethod'])) {
		// 	$rules[] = ['field' => 'selectedPaymentMethod', 'label' => 'Payment Method', 'rules' => 'required'];
		// }
		return $rules;
	}

	public function gridConfig($option = array())
	{
		return [
			'relationships' => [
				'institution' => ['univ', 'institution.univ_id = univ', 'left'],
			],
			'sort' => ['t.created_at', 'desc']
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

	public function account()
	{
		return $this->hasOne("User_account_m", "username", "username_account");
	}

	public function institution()
	{
		return $this->hasOne("Univ_m", "univ_id", "univ");
	}

	public function city_name(){
		return $this->hasOne("Wilayah_m", "kode", "city");
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
	public function getCard($event_id, $member = array())
	{
		require_once APPPATH . "third_party/phpqrcode/qrlib.php";
		if (file_exists(APPPATH . "uploads/nametag_template/$event_id.txt")) {
			$this->load->model(['Settings_m', 'Event_m']);
			if (count($member) == 0) {
				$member = $this->toArray();
				$status = $this->status_member;
				$member['status_member'] = $status->kategory;
			}
			if (!isset($member['qr']))
				$member['qr'] = "card;" . $member['id'] . ";" . $event_id;

			$event = $this->Event_m->findOne($event_id);
			$member['event_name'] = $event->name;
			$member['status_member'] = "Peserta";

			$diff = array_diff(['qr', 'fullname', 'status_member', 'event_name'], array_keys($member));
			if (count($diff) == 0) {
				$domInvoice = new Dompdf\Dompdf();
				$propery = json_decode(Settings_m::getSetting("config_nametag_$event_id"), true);
				$html = $this->load->view("template/nametag", [
					'image' => file_get_contents(APPPATH . "uploads/nametag_template/$event_id.txt"),
					'property' => $propery,
					'data' => $member
				], true);
				$domInvoice->setPaper("A5", "portrait");
				$domInvoice->loadHtml($html);
				$domInvoice->render();
				return $domInvoice;
			} else {
				throw new ErrorException("Parameter " . implode(",", $diff) . " not found !");
			}
		} else {
			throw new ErrorException("Template of nametag not found !");
		}
	}

	/**
	 * @param null $id
	 * @return int
	 */
	public function countFollowedEvent($id = null)
	{
		if ($id == null)
			$id = $this->id;
		$this->load->model("Transaction_m");
		return $this->setAlias("t")->find()->select("count(*) as ev")
			->join("transaction_details td", "td.member_id = t.id")
			->join("transaction tr", "tr.id = td.transaction_id")
			->where("event_pricing_id > 0")
			->where("t.id", $id)
			->where("tr.status_payment", Transaction_m::STATUS_FINISH)
			->get()->row()->ev;
	}
}
