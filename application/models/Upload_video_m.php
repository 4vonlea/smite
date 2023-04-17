<?php


class Upload_video_m extends MY_Model
{
    protected $table = "upload_video";
    protected $primaryKey = "id";
    const TYPE_VIDEO = 1;
    const TYPE_IMAGE = 2;
    const TYPE_LINK = 3;
    const PATH = "themes/uploads/video/";

    public static $types = [
        self::TYPE_VIDEO => 'Video',
        self::TYPE_IMAGE => 'Image',
        // self::TYPE_LINK=>'Link'        
    ];

    public function rules()
    {
        return [
            ['field' => 'filename', 'label' => 'Video/Image', 'rules' => 'required'],
            ['field' => 'title', 'label' => 'Title', 'rules' => 'required|max_length[250]'],
            ['field' => 'type', 'label' => 'Type', 'rules' => 'required'],
            ['field' => 'uploader', 'label' => 'Contestant', 'rules' => 'required|max_length[100]'],
            ['field' => 'description', 'label' => 'Description', 'rules' => 'required'],
        ];
    }

    public function gridConfig($option = array())
    {
        return [
            'select' => ['id' => 't.id', "filename", "uploader", "type", "title", "description", "like_count" => 'jumlah_like', 'comment' => 'jumlah_komen'],
            'relationships' => [
                'likes' => ['(SELECT video_id,COUNT(id) as jumlah_like FROM video_like GROUP BY video_id)', 'video_id = t.id', 'left'],
                'comments' => ['(SELECT video_id,COUNT(id) as jumlah_komen FROM video_komen GROUP BY video_id)', 't.id = comments.video_id', 'left']
            ],
            'disable_search_field' => ['like_count', 'comment'],
        ];
    }

    public function findDetail($id)
    {
        $row = $this->db->select("t.*,count(likes.id) as likeCount")
            ->from($this->table . " t")
            ->join("video_like likes", 't.id = video_id', "left")
            ->where("t.id", $id)
            ->get()->row_array();
        if ($row) {
            $row['comments'] = $this->db->get_where("video_komen", ['video_id' => $id])->result_array();
        }
        return $row;
    }

    public function listvid()
    {
        $sess = $this->session->userdata('user_session');
        $this->db->select('*');
        $this->db->from('upload_video');
        $this->db->order_by('rand()');
        $temp = $this->db->get()->result();
        $result['data'] = array();

        foreach ($temp as $data) {
            $data->like = $this->get_like($data->id);
            $data->komen = $this->get_komen($data->id);
            if (!empty($sess)) {
                $data->ini = $this->getini($data->id);
            }
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

    public function getini($id)
    {
        $sess = $this->session->userdata('user_session');
        $username = $sess['username'];

        $this->db->select('*');
        $this->db->from('video_like');
        $this->db->where('video_id', $id);
        $this->db->where('username', $username);
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
        $sess = $this->session->userdata('user_session');
        $this->db->select('*');
        $this->db->from('upload_video');
        $this->db->where('id', $id);
        $temp = $this->db->get()->result();
        $result['data'] = array();

        foreach ($temp as $data) {
            $data->komen = $this->get_komen($data->id);
            $data->listkomen = $this->list_komen($data->id);
            if (!empty($sess)) {
                $data->ini = $this->getini($data->id);
            }
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
        $query = "INSERT INTO `video_komen`( `comment`) 
    VALUES ('$comment')";
        $this->db->query($query);
    }

    public function delete_komen($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('video_komen');
    }

    public function downloadReport()
    {
        return $this->db->select("uploader as kontestan,title as judul,if(type = 1,'Video','Gambar') as jenis,COALESCE(jumlah_like,0) as jumlah_like,COALESCE(jumlah_komen,0) as jumlah_komen")
            ->from($this->table . " as t")
            ->join("(SELECT video_id,COUNT(id) as jumlah_like FROM video_like GROUP BY video_id) as likes", 'likes.video_id = t.id', 'left')
            ->join("(SELECT video_id,COUNT(id) as jumlah_komen FROM video_komen GROUP BY video_id) as comments", 'comments.video_id = t.id', 'left')
            ->get()->result_array();
    }

    public function fetchVideoAndPhoto()
    {
        $result = $this->findAll();
        $return = ['photo' => [], 'video' => []];
        foreach ($result as $row) {
            if ($row->type == Upload_video_m::TYPE_VIDEO) {
                $rowArray = $row->toArray();
                if (file_exists(self::PATH . "thumbs/" . $rowArray['filename'] . ".png")) {
                    $rowArray['thumbs'] = self::PATH . "thumbs/" . $rowArray['filename'] . ".png";
                } else {
                    $rowArray['thumbs'] = "";
                }
                $return['video'][] = $rowArray;
            } else {
                $tempRow = $row->toArray();
                $listFilename = json_decode($row->filename) ?? [];
                if (count($listFilename) > 0) {
                    $tempRow['filename'] = $listFilename[0];
                }
                $tempRow['list'] = $row->filename;
                $return['photo'][] = $tempRow;
            }
        }
        return $return;
    }
}
