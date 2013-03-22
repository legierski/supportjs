<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_v0_1 extends CI_Controller {

    public function receive_message() {

        $json = array();
        $json['success'] = false;

        $api_key = $this->input->post('api_key');

        $account = $this->accounts_model->get_one_by_api_key($api_key);

        if($account !== false) {

            $full_name = $this->input->post('full_name');
            $email = $this->input->post('email');
            $subject = $this->input->post('subject');
            $message = $this->input->post('message');
            $additional_info = $this->input->post('additional_info');

            $result = $this->emails_model->add_to_queue($account->id, $full_name, $email, $subject, $message, $additional_info);

            if($result === true) {

                $account->emails_counter_total++;
                $account->updated_at = date('Y-m-d H:i:s');

                $this->db->where('id', $account->id);
                $this->db->update('accounts', $account);


                $json['success'] = true;
            }
            else {
                $json['msg'] = 'Oops! Adding email to queue failed. Please contact support@supportjs.com';
            }
        }
        else {
            $json['msg'] = 'Oops! Account not found. Please contact support@supportjs.com';
        }

        echo json_encode($json);
        exit;
    }
}
