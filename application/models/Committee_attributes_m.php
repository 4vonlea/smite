<?php


class Committee_attributes_m extends My_model
{

	protected $primaryKey = "id";
	protected $table = "committee_attribute";

	public function committee()
	{
		return $this->hasOne("Committee_m", "id", "committee_id");
	}

	public function event()
	{
		return $this->hasOne("Event_m", "id", "event_id");
	}

	public function exportCertificate($id = null){
		$com = $this;
		if ($id) {
			$com = $this->findOne($id);
		}
		$event = $com->event;
		$this->load->model('Settings_m');
		$member = [
			'fullname'=>$com->committee->name,
			'status_member'=>$com->status,
			'event_name'=>$event->name,
			'alternatif_status'=>$com->status,
			'alternatif_status2'=>$com->status,
			'qr' => base_url("site/sertifikat_committee")."/".sha1($com->id),
		];
		require_once APPPATH . "third_party/phpqrcode/qrlib.php";
		if(file_exists(APPPATH."uploads/cert_template/$event->id.txt")) {
			$event_id = $event->id;
			$domInvoice = new Dompdf\Dompdf();
			$configuration = json_decode(Settings_m::getSetting("config_cert_$event_id"), true);
			$html = $this->load->view("template/certificate", [
				'image' => file_get_contents(APPPATH . "uploads/cert_template/$event_id.txt"),
				'property' => $configuration['property'] ?? [],
			    'anotherPage'=>$configuration['anotherPage'] ?? [],
				'data' => $member
			], true);
			$domInvoice->setPaper("a4", "landscape");
			$domInvoice->loadHtml($html);
			$domInvoice->render();
			return $domInvoice;
		}else{
			throw new ErrorException("Template of Certificate not found !");
		}
	}

	/**
	 * @param $event
	 * @param array $member
	 * @return \Dompdf\Dompdf
	 */
	public function getCard($id = null)
	{
		require_once APPPATH . "third_party/phpqrcode/qrlib.php";
		$com = $this;
		if ($id) {
			$com = $this->findOne($id);
		}
		$event = $com->event;
		$event_id = $event->id;
		if(file_exists(APPPATH."uploads/nametag_template/$event_id.txt")) {
			$this->load->model('Settings_m');
			$member = [
				'fullname'=>$com->committee->name,
				'status_member'=>$com->status,
				'qr'=>$com->id,
				'event_name'=>$event->name,
			];
			$diff = array_diff(['qr','fullname','status_member','event_name'],array_keys($member));
			if(count($diff) == 0) {
				$domInvoice = new Dompdf\Dompdf();
				$configuration = json_decode(Settings_m::getSetting("config_nametag_$event_id"), true);
				$html = $this->load->view("template/nametag", [
					'image' => file_get_contents(APPPATH . "uploads/nametag_template/$event_id.txt"),
					'data' => $member,
					'property'=>$configuration
				], true);
				$domInvoice->setPaper("A5", "portrait");
				$domInvoice->loadHtml($html);
				$domInvoice->render();
				return $domInvoice;
			}else{
				throw new ErrorException("Parameter ".implode(",",$diff)." not found !");

			}
		}else{
			throw new ErrorException("Template nametag not found !");
		}
	}
}
