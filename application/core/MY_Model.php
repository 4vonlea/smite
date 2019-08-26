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

    public function gridData($params,$relationship = [])
    {
        $global_filter = (isset($params['global_filter'])?$params['global_filter']:null);
        $fields = $params['fields'];
        $sort = explode("|",$params['sort']);
        $limit = $params['per_page'];
        $offset = ($params['page'] - 1)*$limit;

        $builder = $this->find()->limit($limit)->offset($offset);
        $countBuilder = $this->getBuilder();

        if(count($sort) > 1)
            $builder->order_by($sort[0],$sort[1]);

        if($global_filter){
            foreach($fields as $fname){
                $builder->or_like($fname,$global_filter);
                $countBuilder->or_like($fname,$global_filter);
            }
        }

        $result =   $builder->get();

        $data['data'] = $result->result_array();
        $data['total'] =  $countBuilder->count_all_results($this->tableName());
        $data['per_page'] = $limit;
        $data['current_page'] = $params['page'];
        $data['url'] = current_url();

        return $data;
    }

    public static function asList($object,$id,$value ,$placeholder = ''){
        $return = [];
        if($placeholder != ''){
            $return[''] = $placeholder;
        }
        if(is_array($object)){
            foreach($object as $row){
                $key = is_object($row) ? $row->{$id}: is_array($row) ? $row[$id]:"";
                $val = is_object($row) ? $row->{$value}: is_array($row) ? $row[$value]:"";
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