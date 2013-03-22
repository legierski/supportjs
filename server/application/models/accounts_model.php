<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_one_by_api_key($api_key) {

        $query = $this->db->select('a.*')
                            ->from('accounts a')
                            ->where('a.api_key', $api_key)
                            ->limit(1)
                            ->get();

        if($query->num_rows() > 0) {
            $accounts = $query->result();
            return $accounts[0];
        }
        else {
            return false;
        }
    }

    public function get_by_id($account_id) {

        $query = $this->db->select('a.*')
                            ->from('accounts a')
                            ->where('a.id', $account_id)
                            ->limit(1)
                            ->get();

        if($query->num_rows() > 0) {
            $accounts = $query->result();
            return $accounts[0];
        }
        else {
            return false;
        }
    }

}
