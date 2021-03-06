<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function process_emails_queue() {

        redirect_if_not_cli();

        for($i=0; $i<20; $i++) {
            $this->_send_email();
            sleep(2); // Amazon allows 1 email per second in sandbox mode
        }
    }

    private function _send_email() {

        $email = $this->emails_model->get_one();

        if($email === false) {
            die('No emails to process'."\n");
        }

        if(filter_var($email->client_email, FILTER_VALIDATE_EMAIL)) {

            $this->emails_model->mark_as_in_progress($email->id);

            $account = $this->accounts_model->get_by_id($email->account_id);

            if($account !== false) {

                $data = array(
                    'client_name' => $email->client_name,
                    'client_email' => $email->client_email,
                    'subject' => $email->subject,
                    'body' => $email->body,
                    'additional_info' => json_decode($email->additional_info, true)
                    );

                send_mail($account->email, 'client_email', $data);

                echo 'Email sent'."\n";
            }
            else {
                echo 'Account not found'."\n";
            }
        }
        else {
            echo 'Invalid email address'."\n";
        }

        $this->emails_model->delete_by_id($email->id);
    }
}
