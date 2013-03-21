<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function send_mail($to_email, $type, $data) {

    $CI = &get_instance();

    $from_name = 'SupportJS';
    $from_email = 'support@supportjs.com';
    $reply_to_email = '';

    $subject = '[SupportJS]';
    $body = '';

    switch($type) {

        case 'client_email':
            $from_name = $data['client_name'];
            $from_email = 'messages@supportjs.com';
            $reply_to_email = $data['client_email'];

            $subject = $data['subject'];
            $body = $data['body'] . '<br><br>';

            if(isset($data['additional_info'])) {
                if(foreach_safe($data['additional_info'])) {

                    $body .=    '---------------------------------------------------------------------------------<br><br>' .
                                'Additional info:<br><br>';
                    foreach($data['additional_info'] as $info_name => $info_value) {
                        $info_name = ucfirst($info_name);
                        $info_name = str_replace('_', ' ', $info_name);

                        $body .= $info_name.': '.$info_value.'<br>';
                    }

                    $body .= '<br>';
                }
            }

            $body .=    '---------------------------------------------------------------------------------<br><br>' .

                        'Message sent via SupportJS - http://supportjs.com';

            break;
    }

    if(!dev()) {
        $CI->amazon_ses->to($to_email);
        $CI->amazon_ses->from($from_email, $from_name);
        $CI->amazon_ses->reply_to($reply_to_email);
        $CI->amazon_ses->subject($subject);
        $CI->amazon_ses->message($body);
        $CI->amazon_ses->send();
    }
    else {
        phpconsole(array(
            'to_email' => $to_email,
            'from_name' => $from_name,
            'from_email' => $from_email,
            'reply_to_email' => $reply_to_email,
            'subject' => $subject,
            'body' => $body
            ), 'peter');
    }



}
