<?php

class LogQuery {

    function write() {
        $CI = & get_instance();
        $db = $CI->load->database('',true);
        $username = $CI->session->user_session['username'] ?? "-";
        $ip = $CI->input->ip_address();
        $request = json_encode(array_merge($CI->input->get(),$CI->input->post()));
        $controller = $CI->input->server('REQUEST_URI');
        foreach ($CI->db->queries as $key => $query) {
            $date = date('Y-m-d H:i:s');
            if (!in_array(strtoupper(substr(trim($query),0,6)),["SELECT","SHOW C"])) {
                $db->insert('log_proses',array(
                    'controller'=>$controller ?? "",
                    'username'=>$username.":".$ip,
                    'request'=>$request,
                    'query'=>$query,
                    'date'=>$date,
                ));
            }
        }
 
    }
}