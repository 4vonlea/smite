<?php


class Upload_video_m extends MY_Model
{
    protected $table = "upload_video";
    protected $primaryKey = "id";
    const TYPE_VIDEO = 1;
    const TYPE_IMAGE = 2;
    const TYPE_LINK = 3;

    public static $types = [
        self::TYPE_VIDEO=>'Video',
        self::TYPE_IMAGE=>'Image',
        // self::TYPE_LINK=>'Link'        
    ];

    public function rules(){
        return [
			['field' => 'filename','label'=>'Video/Image','rules' => 'required|max_length[100]'],
			['field' => 'title','label'=>'Title','rules' => 'required|max_length[250]'],
			['field' => 'type','label'=>'Type','rules' => 'required'],
			['field' => 'uploader','label'=>'Contestant','rules' => 'required|max_length[100]'],
			['field' => 'description','label'=>'Description','rules' => 'required'],
        ];
    }

    public function gridConfig($option = array())
	{
		return [
			'select'=>['id'=>'t.id',"filename","uploader","type","title","description","like_count"=>'COUNT(likes.id)','comment'=>'count(comments.id)'],
			'relationships' => [
				'likes' => ['video_like', 'vidoe_id = t.id','left'],
				'comments' => ['video_komen', 't.id = comments.video_id','left']
            ],
            'group_by'=>'t.id',
		];
    }
    
    public function findDetail($id){
        $row = $this->db->select("t.*,count(likes.id) as likeCount")
                    ->from($this->table." t")
                    ->join("video_like likes",'t.id = vidoe_id',"left")
                    ->where("t.id",$id)
                    ->get()->row_array();
        if($row){
            $row['comments'] = $this->db->get_where("video_komen",['video_id'=>$id])->result_array();
        }
        return $row;
    }
}