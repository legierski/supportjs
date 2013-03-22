<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function phpconsole_init() {
    $CI =& get_instance();

    if(!$CI->phpconsole->is_initialized()) {
        $CI->phpconsole->set_backtrace_depth(1);

        /*
        ==============================================
        USER'S SETTINGS
        ==============================================
        */

        $CI->phpconsole->set_path_to_cert('application/certs/cacert.pem');
        $CI->phpconsole->set_domain('.supportjs.com');
        $CI->phpconsole->add_user('nickname', 'user_api_key', 'project_api_key'); // you can add more developers, just execute another add_user()

    }
}

function phpconsole($data_sent, $user = false) {
    phpconsole_init();

    $CI =& get_instance();
    return $CI->phpconsole->send($data_sent, $user);
}

function phpcounter($number = 1, $user = false) {
    phpconsole_init();

    $CI =& get_instance();
    $CI->phpconsole->count($number, $user);
}

function phpconsole_cookie($name) {
    phpconsole_init();

    $CI =& get_instance();
    $CI->phpconsole->set_user_cookie($name);
}

function phpconsole_destroy_cookie($name) {
    phpconsole_init();

    $CI =& get_instance();
    $CI->phpconsole->destroy_user_cookie($name);
}
