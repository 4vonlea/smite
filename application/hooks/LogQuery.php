<?php

class LogQuery
{

    function write()
    {
        $CI = &get_instance();
        $db = $CI->load->database('', true);
        $username = $CI->session->user_session['username'] ?? "-";
        $ip = $CI->input->ip_address();
        $postData = $CI->input->post();
        unset($postData['secondPage']);
        $request = json_encode(array_merge($CI->input->get(), $postData));
        $controller = $CI->input->server('REQUEST_URI');
        $tempQuery = [];
        foreach ($CI->db->queries as $key => $query) {
            $date = date('Y-m-d H:i:s');
            $hashQuery = sha1($query);
            if (!in_array($hashQuery, $tempQuery)) {
                $tempQuery[] = $hashQuery;
                if (!in_array(strtoupper(substr(trim($query), 0, 6)), ["SELECT", "SHOW C"])) {
                    $db->insert('log_proses', array(
                        'controller' => $controller ?? "",
                        'username' => $username . ":" . $ip,
                        'request' => $request,
                        'query' => $query,
                        'date' => $date,
                    ));
                }
            }
        }
    }
}
