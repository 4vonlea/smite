<?php

/**
 * Class Papers_m
 *
 * Introduction as Abstract
 * Methods as type_study
 */

class Papers_m extends MY_Model
{
	const ACCEPTED = 2;
	const REJECTED = 3;
	const RETURN_TO_AUTHOR = 0;
	public static $status = [
		self::RETURN_TO_AUTHOR => 'Returned to author',
		1 => 'Waiting/Under Review',
		self::ACCEPTED => 'Accepted',
		self::REJECTED => 'Rejected'
	];

	public static $typePresentation = [
		'Oral' => 'Oral',
		'Moderated Poster' => 'Moderated Poster',
		'Viewed Poster' => 'Viewed Poster',
		'Voice Recording' => 'Voice Recording',
	];

	public static $typeAbstract = [
		//		'Final Paper' => 'Final Paper',
		'Free Paper' => 'Free Paper',
		//		'Poster' => 'Poster',
	];

	public static $typeStudy = [
		'Case Report' => 'Case Report',
		// 'Review Article'=>'Review Article',
		'Original Research' => 'Original Research',
		'Systematic Review / Meta Analysis' => 'Systematic Review / Meta Analysis',
		// 'Other' =>'Other',
	];

	public static $declaration = [
		"Research"=>"I hereby declared that the mentioned study has granted ethical clearance from an official ethical committee.",
		"Clinical Pathogical Conference"=>"I hereby declared that the mentioned study has granted ethical clearance from an official ethical committee",
		"Original Research"=>"I hereby declared that the mentioned study has granted ethical clearance from an official ethical committee",
		"Systematic Review"=>"I hereby declared that this submitted review has made according to PRISMA Statement",
		"Case Report"=>"I hereby declared that the mentioned case report has granted informed consent to be published from the patient or the responsible caregiver and all possible effort to obscure patient's identity has been taken."
	];

	protected $table = "papers";

	public function rules()
	{
		return [
			['field' => 'title', 'label' => 'Title', 'rules' => 'required|max_length[255]'],
			['field' => 'type', 'label' => 'Type Abstract', 'rules' => 'required|max_length[255]'],
			['field' => 'introduction', 'label' => 'Abstract', 'rules' => ['required', [
				'max_word', function ($value) {
					return (count(explode(" ", trim($value))) <= 300);
				}
			]], 'errors' => ['max_word' => '{field} Maksimal 300 Kata']],
			//			['field' => 'aims', 'label' => 'aims', 'rules' => 'required'],
			['field' => 'methods', 'label' => 'Type Of Study', 'rules' => 'required'],
			['field' => 'category', 'label' => 'Category Paper', 'rules' => 'required'],
			//			['field' => 'result', 'label' => 'result', 'rules' => 'required'],
			//			['field' => 'conclusion', 'label' => 'conclusion', 'rules' => 'required'],
			//			['field' => 'type_presence', 'label' => 'Mode Of Presentation', 'rules' => 'required'],
		];
	}

	public function gridConfig($option = array())
	{
		$default =  [
			'relationships' => [
				'member' => ['members', 'member.id = member_id'],
				'category_paper' => ['category_paper', 'category_paper.id = category', 'left'],
				'st' => ['settings', 'st.name = "format_id_paper"', "left"]
			],
			'select' => [
				'st_value' => 'st.value',
				'id_paper' => 'CONCAT(st.value,LPAD(t.id,3,0))',
				't_id' => 't.id', 'fullname',
				'title',
				'status' => 't.status',
				't_created_at' => 't.created_at',
				'm_id' => 'member.id',
				'author' => 'member.fullname',
				'category_name' => 'category_paper.name',
				'category_id' => 'category_paper.id',
				'filename',
				'reviewer',
				'introduction',
				'aims',
				'methods',
				'conclusion',
				'co_author',
				'result',
				'message',
				'feedback',
				'type_presence',
				'type',
				'fullpaper',
				'poster',
				"status_fullpaper",
				"status_presentasi",
				"feedback_file_fullpaper",
				"feedback_fullpaper",
				"feedback_presentasi",
				"feedback_file_presentasi",
				'score'
			]
		];
		$config =  array_merge($default, $option);
		return $config;
	}

	public function getIdPaper($id = null)
	{
		if (isset($this->id) && $id == null) {
			$id = $this->id;
		}
		return Settings_m::getSetting('format_id_paper') . str_pad($id, 3, 0, STR_PAD_LEFT);
	}

	public function gridData($params, $gridConfig = [])
	{
		$data = parent::gridData($params, $gridConfig);
		$db = $this->find()->select("SUM(IF(status = 0,1,0)) as stat_0")
			->select("SUM(IF(status_fullpaper = 0,1,0)) as stat_fullpaper_0")
			->select("SUM(IF(status_fullpaper = 1,1,0)) as stat_fullpaper_1")
			->select("SUM(IF(status_fullpaper = 2,1,0)) as stat_fullpaper_2")
			->select("SUM(IF(status_fullpaper = 3,1,0)) as stat_fullpaper_3")
			->select("SUM(IF(status_presentasi = 0,1,0)) as stat_presentasi_0")
			->select("SUM(IF(status_presentasi = 1,1,0)) as stat_presentasi_1")
			->select("SUM(IF(status_presentasi = 2,1,0)) as stat_presentasi_2")
			->select("SUM(IF(status_presentasi = 3,1,0)) as stat_presentasi_3")
			->select("SUM(IF(status = 1,1,0)) as stat_1")
			->select("SUM(IF(status = 2,1,0)) as stat_2")
			->select("SUM(IF(status = 3,1,0)) as stat_3");


		if (isset($gridConfig['filter']))
			$db->where($gridConfig['filter']);

		$result = $db->get()->row_array();
		$data = array_merge($data, $result);
		$data['total_stat_0'] = isset($result['stat_0']) ? $result['stat_0'] : 0;
		$data['total_stat_1'] = isset($result['stat_1']) ? $result['stat_1'] : 0;
		$data['total_stat_2'] = isset($result['stat_2']) ? $result['stat_2'] : 0;
		$data['total_stat_3'] = isset($result['stat_3']) ? $result['stat_3'] : 0;


		$model = $this->find()->group_start()
			->where("reviewer", "")->or_where("reviewer IS NULL", null)->group_end()
			->where("status", 1)->select("count(*) as r")->get();
		$data['total_no_reviewer'] = $model->row()->r;

		$model = $this->find()->where("status", self::ACCEPTED)->group_by('type_presence')->select("type_presence,count(id) as total")->get();
		foreach (self::$typePresentation as $row) {
			$data['presentation_accepted'][$row] = 0;
		}
		foreach ($model->result_array() as $row) {
			if ($row['type_presence'] == "") {
				$row['type_presence'] = "Not Set";
			}
			$data['presentation_accepted'][$row['type_presence']] = $row['total'];
		}

		return $data;
	}

	/**
	 * @return Dompdf
	 */
	public function exportNotifPdf()
	{
		$template = ($this->status == Papers_m::ACCEPTED ? 'template/paper_accepted' : 'template/paper_rejected');

		$domInvoice = new Dompdf\Dompdf();
		$domInvoice->setPaper('legal');

		$html = $this->load->view($template, ['paper' => $this, 'member' => $this->member], true);

		$option = new \Dompdf\Options();
		$option->setIsRemoteEnabled(true);
		$domInvoice->setOptions($option);

		$domInvoice->loadHtml($html);
		$domInvoice->render();
		return $domInvoice;
	}

	public function getParticipant()
	{
		return $this->setAlias("t")->find()
			->join("members m", "m.id = t.member_id")
			->select("m.*");
	}

	public function member()
	{
		return $this->hasOne('Member_m', 'id', 'member_id');
	}

	public function findAllPoster()
	{
		$result = $this->setAlias("t")->find()
			->select("t.id,title,type_presence as type,m.fullname,poster")
			->join("members m", "m.id = t.member_id")
			->where("t.status", self::ACCEPTED)
			->where("poster IS NOT NULL")
			->get();
		return $result->result_array();
	}

	public function count_paper()
	{
		$this->db->select('*');
		$this->db->from('papers');
		$this->db->where('status', '2');
		$result = $this->db->get();
		return $result->num_rows();
	}

	public function category_paper()
	{
		return $this->hasOne("Category_paper_m", "id", "category");
	}
}
