<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function dev() {
    return ENVIRONMENT == 'development';
}

function is_cli() {

    $CI = &get_instance();

    return $CI->input->is_cli_request();
}

function redirect_if_not_cli() {
    if(!is_cli()) {
        redirect();
    }
}

function foreach_safe($array) {
    if(is_array($array)) {
        if(count($array) > 0) {
            return true;
        }
    }

    return false;
}
