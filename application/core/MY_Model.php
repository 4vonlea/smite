<?php


class MY_Model extends yidas\Model
{
    protected $dateFormat = "datetime";

    /**
     * @return array
     */
    public function getErrors()
    {
        return is_array(parent::getErrors()) ? parent::getErrors() : [];
    }

    public function gridData($params)
    {
        $limit = $params['per_page'];
        $offset = $params['page'] - 1;
        $global_search = $params['global_search'];

        $result = $this->find()->limit($limit)
            ->offset($offset)
            ->get();

        $count = $this->count();
        return ['rows'=>$result->result_array(),'total_rows'=>$count];
    }
}