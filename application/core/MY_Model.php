<?php


class MY_Model extends yidas\Model
{
    protected $dateFormat = "datetime";
    public $fillable = [];

    /**
     * @return array
     */
    public function getErrors()
    {
        return is_array(parent::getErrors()) ? parent::getErrors() : [];
    }

    public function errorsString($prefix = '',$suffix = ''){
		if (count($this->getErrors()) === 0)
		{
			return '';
		}
		// Generate the error string
		$str = '';
		foreach ($this->getErrors() as $val)
		{
			if ($val !== '')
			{
				$str .= $prefix.$val.$suffix."\n";
			}
		}

		return $str;
	}

    private function convertGridSelect($select){
		$fields = [];
		foreach($select as $alias=>$f){
			if(is_numeric($alias)){
				$fields[] = $f;
			}else{
				$fields[] = "$f as $alias";
			}
		}
		return $fields;
	}
	/**
	 * @return array contain paremeter with key (relationships,select,where);
	 */
    public function gridConfig($option = array()){
    	return [];
	}

    public function gridData($params,$gridConfig = [])
    {
		$gridConfig = (count($gridConfig) > 0 ? $gridConfig:$this->gridConfig());
		$global_filter = (isset($params['global_filter'])?$params['global_filter']:null);
        $sort = explode("|",$params['sort']);
        $limit = $params['per_page'];
        $offset = ($params['page'] - 1)*$limit;
        if(isset($gridConfig['include_search_field']))
        	$fields = array_merge($gridConfig['include_search_field'],$params['fields']);
        else
        	$fields = $params['fields'];

        if(isset($gridConfig['disable_search_field'])){
        	$fields = array_diff($fields,$gridConfig['disable_search_field']);
		}
		/**
		 * @var $builder CI_DB_query_builder
		 */
		$countBuilder = clone $this->getBuilder();
		$countBuilder->select("t.*");
		$builder = $this->setAlias("t")->find()->limit($limit)->offset($offset);
		if(isset($gridConfig['select']))
			$builder->select($this->convertGridSelect($gridConfig['select']));

        if(count($sort) > 1)
            $builder->order_by($sort[0],$sort[1]);

        if($global_filter){
        	$builder->group_start();
        	$countBuilder->group_start();
            foreach($fields as $fname){
            	$aliasName = $fname;
            	$fname = isset($gridConfig['select'][$fname])?$gridConfig['select'][$fname]:$fname;
            	if(isset($gridConfig['search_operator'][$aliasName])){
            		$operator = $gridConfig['search_operator'][$aliasName];
            		switch ($operator){
						case "=":
							$builder->or_where($fname, $global_filter);
							$countBuilder->or_where($fname, $global_filter);
							break;
						default:
							$builder->or_like($fname, $global_filter);
							$countBuilder->or_like($fname, $global_filter);
							break;
					}
				}else {
					$builder->or_like($fname, $global_filter);
					$countBuilder->or_like($fname, $global_filter);
				}
            }
			$builder->group_end();
			$countBuilder->group_end();
		}

		if(isset($gridConfig['relationships'])){
			foreach($gridConfig['relationships'] as $alias=>$r){
				$builder->join("$r[0] as $alias","$r[1]",(isset($r[2])?"$r[2]":"inner"));
				$countBuilder->join("$r[0] as $alias","$r[1]",(isset($r[2])?"$r[2]":"inner"));
			}
		}

		if(isset($gridConfig['filter'])){
			$builder->where($gridConfig['filter']);
			$countBuilder->where($gridConfig['filter']);
		}

		if(isset($gridConfig['group_by'])){
			$builder->group_by($gridConfig['group_by']);
			$countBuilder->group_by($gridConfig['group_by']);

		}

        $result = $builder->get();
        $data['data'] = $result->result_array();
		$data['total'] =  $countBuilder->count_all_results($this->tableName()." as t");
        $data['per_page'] = $limit;
        $data['current_page'] = $params['page'];
        $data['url'] = current_url();

        return $data;
    }

	/**
	 * @param $data
	 * @param $key_field
	 * @return array
	 * @throws Exception
	 */
    public static function withKey($data,$key_field){
    	$return = [];
    	if(!is_array($data))
    		throw new Exception("Parameter data is not array");
    	foreach($data as $row){
    		$return[$row[$key_field]] = $row;
		}
    	return $return;
	}

    public static function asList($object,$id,$value ,$placeholder = ''){
        $return = [];
        if($placeholder != ''){
            $return[''] = $placeholder;
        }
        if(is_array($object)){
            foreach($object as $row){
                $key = is_object($row) ? $row->{$id}: (is_array($row) ? $row[$id]:"");
                $val = is_object($row) ? $row->{$value}: (is_array($row) ? $row[$value]:"");
                $return[$key] = $val;
            }
        }
        return $return;
    }

    public function setAttributes($attributes){
        if(count($this->fillable) > 0)
            $attributes = array_intersect_key($attributes,array_flip($this->fillable));

        foreach($attributes as $name=>$value) {
            $this->{$name} = $value;
        }
    }

}

interface iNotification{
	public function sendMessage($to,$subject,$message);
}
