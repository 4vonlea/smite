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
        self::TYPE_LINK=>'Link'        
    ];

    public function rules(){
        return [
			['field' => 'title','label'=>'Title','rules' => 'required|max_length[100]'],
        ];
    }

    public function gridConfig($option = array())
	{
		return [
			'select'=>['id'=>'t.id',"filename","title","description","like_count"=>'COUNT(likes.id)','comment'=>'count(comments.id)'],
			'relationships' => [
				'likes' => ['video_like', 'vidoe_id = t.id'],
				'comments' => ['video_komen', 't.id = comments.video_id']
            ],
            'group_by'=>'t.id',
		];
	}
}