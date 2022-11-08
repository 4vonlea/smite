<?php


class Sponsor_stand_m extends My_model
{
	protected $table = "stand_sponsor";
	protected $primaryKey = "id";

	public $fillable = ['id','sponsor'];

    protected $timestamps = false;

	public function gridConfig($option = array()){
		return [];
	}

	public function rules()
	{
		return [
			['field' => 'id', 'label' => 'ID', 'rules' => 'required|max_length[250]'],
			['field' => 'sponsor', 'label' => 'Sponsor Name', 'rules' => 'required|max_length[250]'],
		];
	}

	public function getQrCard($id = null){
		if($id == null){
			$data = $this;
		}else{
			$data = $this->findOne($id);
		}
		require_once APPPATH . "third_party/phpqrcode/qrlib.php";
		$params = $data->toArray();
		$params['siteTitle'] = Settings_m::getSetting('site_title');
		$params['logoUrl'] =base_url('themes/uploads/logo.png');

		$domInvoice = new Dompdf\Dompdf();

		$html = $this->load->view("template/qr_stand",$params,true);
		$domInvoice->setPaper("A5", "portrait");
		$domInvoice->loadHtml($html);
		$domInvoice->render();
		
	}

}
