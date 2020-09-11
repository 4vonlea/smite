<?php


class Ref_upload_m extends MY_Model
{
    protected $table = "ref_upload";
    protected $primaryKey = "id";

    public function gridConfig($option = array())
	{
        $statustoUpload = Settings_m::getSetting("status_to_upload");
        $statustoUpload = ($statustoUpload == "" ? [0]:json_decode($statustoUpload));
		return [
			'select'=>['t_id'=>'t.id','fullname'=>'member.fullname',"mum.filename","title","type"=>"mum.type","id_mum"=>'mum.id'],
			'relationships' => [
				'member' => ['members', '1 = 1'],
				'mum' => ['member_upload_material', 'mum.member_id = member.id AND mum.ref_upload_id = t.id','left']
            ],
            'filter'=>['member.status IN ('.implode(",",$statustoUpload).")"=>null]
		];
	}

    
    public function getListMaterialMember($member_id){
        $rs = $this->setAlias("t")->find()
                ->select("t.id,t.title,mum.id as id_mum,mum.filename,COALESCE(mum.type,2) as type,'' as tempname,deadline,'0' as countdown")
                ->join("member_upload_material mum","mum.ref_upload_id = t.id AND mum.member_id = '$member_id'","left")
                ->get();
        return $rs->result_array();
    }

}