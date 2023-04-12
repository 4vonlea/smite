<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{

    protected $current_row = [];

    public function run($group = '')
    {
        $this->current_row = empty($this->validation_data) ? $_POST : $this->validation_data;
        return parent::run($group);
    }

    public function is_unique_or_match($str, $field)
    {
        list($table, $fieldCurrent, $fieldMatch) = explode(",", $field);
        if (isset($this->CI->db)) {
            $row = $this->CI->db->limit(1)->get_where($table, [$fieldCurrent => $str])->row();
            if ($row) {
                return $row->{$fieldMatch} == $this->current_row[$fieldMatch] ?? "";
            }
            return true;
        }
        return false;
    }
}
