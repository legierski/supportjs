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

function utf8_wordwrap($string, $width = 75, $break = "\n", $cut = false) {

    if($cut) {
        // Match anything 1 to $width chars long followed by whitespace or EOS,
        // otherwise match anything $width chars long
        $search = '/(.{1,'.$width.'})(?:\s|$)|(.{'.$width.'})/uS';
        $replace = '$1$2'.$break;
    } else {
        // Anchor the beginning of the pattern with a lookahead
        // to avoid crazy backtracking when words are longer than $width
        $search = '/(?=\s)(.{1,'.$width.'})(?:\s|$)/uS';
        $replace = '$1'.$break;
    }

    return preg_replace($search, $replace, $string);
}
