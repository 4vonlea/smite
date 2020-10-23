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
				'likes' => ['video_like', 'video_id = t.id','left'],
				'comments' => ['video_komen', 't.id = comments.video_id','left']
            ],
            'group_by'=>'t.id',
		];
    }
    
    public function findDetail($id){
        $row = $this->db->select("t.*,count(likes.id) as likeCount")
                    ->from($this->table." t")
                    ->join("video_like likes",'t.id = video_id',"left")
                    ->where("t.id",$id)
                    ->get()->row_array();
        if($row){
            $row['comments'] = $this->db->get_where("video_komen",['video_id'=>$id])->result_array();
        }
        return $row;
    }

    public function listvid()
    {
        $this->db->select('*');
        $this->db->from('upload_video');
        $this->db->order_by('rand()');
        $temp = $this->db->get()->result();
        $result['data'] = array();

        foreach ($temp as $data) {
            $data->like = $this->get_like($data->id);
            $data->komen = $this->get_komen($data->id);
            $result['data'][] = $data;
        }
        
        return $result;
    }

    public function listvid_home()
    {
        $this->db->select('*');
        $this->db->from('upload_video');
        $this->db->limit(3);
        $this->db->order_by('rand()');
        $temp = $this->db->get()->result();
        $result['data'] = array();

        foreach ($temp as $data) {
            $data->like = $this->get_like($data->id);
            $data->komen = $this->get_komen($data->id);
            $result['data'][] = $data;
        }
        
        return $result;
    }

    public function get_like($id)
    {
        $this->db->select('*');
        $this->db->from('video_like');
        $this->db->where('video_id', $id);
        $result = $this->db->get();
        return $result->num_rows();
    }

    public function get_komen($id)
    {
        $this->db->select('*');
        $this->db->from('video_komen');
        $this->db->where('video_id', $id);
        $result = $this->db->get();
        return $result->num_rows();
    }

    public function seevideo($id)
    {
        $this->db->select('*');
        $this->db->from('upload_video');
        $this->db->where('id', $id);
        $temp = $this->db->get()->result();
        $result['data'] = array();

        foreach ($temp as $data) {
            $data->komen = $this->get_komen($data->id);
            $data->listkomen = $this->list_komen($data->id);
            $result['data'][] = $data;
        }
        // debug($result);
        return $result;
    }

    public function list_komen($id)
    {
        $this->db->select('vk.id as idkomen, vk.username as komen_username, vk.comment as komentar, vk.created_at as waktu, m.fullname as nama, m.id as idmember, m.image as foto');
        $this->db->from('video_komen vk');
        $this->db->join('members m', 'm.email = vk.username');
        $this->db->where('vk.video_id', $id);
        $this->db->order_by('vk.id', 'ASC');

        $result = $this->db->get()->result();
        return $result;
    }

    public function saverecords($name)
    {
        $query="INSERT INTO `video_komen`( `comment`) 
        VALUES ('$comment')";
        $this->db->query($query);
    }

    public function delete_komen($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('video_komen');
    }
}