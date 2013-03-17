<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emails_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function add_to_queue($account_id, $client_name, $client_email, $subject, $body, $additional_info) {

        $data = array(
            'account_id' => $account_id,
            'client_name' => $client_name,
            'client_email' => $client_email,
            'subject' => $subject,
            'body' => $body,
            'additional_info' => json_encode($additional_info),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
            );

        $this->db->insert('emails', $data);

        if($this->db->affected_rows() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

}
