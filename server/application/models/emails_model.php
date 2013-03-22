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

    public function get_one() {

        $query = $this->db->select('e.*')
                            ->from('emails e')
                            ->where('e.in_progress', 'N')
                            ->limit(1)
                            ->order_by('e.id', 'ASC')
                            ->get();

        if($query->num_rows() > 0) {
            $emails = $query->result();
            return $emails[0];
        }
        else {
            return false;
        }
    }

    public function mark_as_in_progress($email_id) {

        $query = $this->db->select('e.*')
                            ->from('emails e')
                            ->where('e.id', $email_id)
                            ->limit(1)
                            ->get();

        if($query->num_rows() > 0) {
            $emails = $query->result();
            $email = $emails[0];

            $email->in_progress = 'Y';

            $this->db->where('id', $email->id);
            $this->db->update('emails', $email);

            return true;
        }
        else {
            return false;
        }
    }

    public function delete_by_id($email_id) {

        $data = array(
            'id' => $email_id,
            );

        $this->db->delete('emails', $data);

        if($this->db->affected_rows() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

}
