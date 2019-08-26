<?php

class Event_m extends MY_Model
{
    protected $table = "events";
    public function rules()
    {
        return [
            ['field' => 'name', 'label' => 'Event Name', 'rules' => 'required'],
            ['field' => 'kategory', 'label' => 'Event Category', 'rules' => 'required'],
        ];
    }

    public function event_pricings()
    {
        return $this->hasMany('Event_pricing_m', 'event_id');
    }

    public function listcategory()
    {
        // $this->db->select('kate.kategory as kategory, eve.name as acara, pri.name as pricing, pri.condition as kondisi');
        // $this->db->from('kategory_members kate');
        // $this->db->join('event_pricing pri', 'pri.condition = kate.kategory', 'left');
        // $this->db->join('events eve', 'eve.id = pri.event_id');
        // $this->db->group_by('kategory');
        // $this->db->order_by('kategory, acara');
        // $temp = $this->db->get()->result();
        $this->db->select('value');
        $this->db->from('settings');
        $temp = $this->db->get()->result();
        foreach ($temp as $key) {
            $key->value;
        }
        $json = $key->value;
        $a    = json_decode($json, true);

        
        $result['data'] = array();

        foreach ($a as $data) {
            $data[1];
        }
        debug($data);
        return $result;

        // foreach ($a as $data) {
        //     $data->acara   = $this->get_acara($data->kondisi);
        //     $result['data'][] = $data;
        //     $temp2 = $data->acara;
        //     foreach ($temp2 as $data2) {
        //         $data2->id_acara   = $this->get_pricing($data2->id_acara);
        //         $result2['data2'][] = $data2;
        //     }
        // }
        // return $result;
    }

    public function get_seting($id)
    {
        $this->db->select('pri.event_id as id_acara, eve.name as nama_acara');
        $this->db->from('event_pricing pri');
        $this->db->join('events eve', 'eve.id = pri.event_id');
        $this->db->where('condition', $id);
        $this->db->group_by('nama_acara');
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_acara($id)
    {
        $this->db->select('pri.event_id as id_acara, eve.name as nama_acara, eve.kategory as kategori');
        $this->db->from('event_pricing pri');
        $this->db->join('events eve', 'eve.id = pri.event_id');
        $this->db->where('condition', $id);
        $this->db->group_by('nama_acara');
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_pricing($id)
    {
        $this->db->select('pri.name as jenis_harga, pri.condition_date as waktu_berlaku, pri.price as harga');
        $this->db->from('event_pricing pri');
        $this->db->where('pri.event_id', $id);
        $this->db->group_by('jenis_harga');
        $result = $this->db->get()->result();
        return $result;
    }

}
