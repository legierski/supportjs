<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_v0_1 extends CI_Controller {

    public function receive_message() {

        $json = array();
        $json['success'] = true;
        echo json_encode($json);
        exit;
    }
}
