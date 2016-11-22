<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public $segment;
    public $data;

    function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Seoul'); // 시간 설정
        $this->load->library('session');
        $this->load->helper('url');

        $this->segment = $this->uri->segment_array();
        $this->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        $dbconfig_path = MAIN_DIRECTORY.'/dbconfig.xml';
        $redirect_url = MAIN_URL;

        if(!empty($this->segment) && $this->segment[1] == 'install') {
          $bool = true;
        } else {
          $bool = false;
          $redirect_url .= '/install';
        }

        if(file_exists($dbconfig_path) == $bool) {
           redirect($redirect_url);
        }
    }
}
