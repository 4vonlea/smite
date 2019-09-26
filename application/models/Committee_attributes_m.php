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

	public function getCard($id = null){
		require_once APPPATH . "third_party/phpqrcode/qrlib.php";
		$com = $this;
		if ($id) {
			$com = $this->findOne($id);
		}
		$event = $com->event;
		$html = $this->load->view("template/member_card", ['event' => $event->toArray(), 'member' => [
			'fullname'=>$com->committee->name,
			'status'=>$com->status
		]], true);
		$dompdf = new Dompdf\Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->render();
		$dompdf->setPaper("A5", "portrait");
		return $dompdf;
	}
}
