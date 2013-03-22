<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hooks {

    public function enable_profiler() {
        if(dev()) {
            $this->CI = &get_instance();
            $this->CI->output->enable_profiler(TRUE);
        }
    }

}
