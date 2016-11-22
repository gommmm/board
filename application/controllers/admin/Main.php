<?php

class Main extends MY_Controller {
    public function __construct() {
	    parent::__construct();
		  $this->load->library('session');
      $this->load->helper('alert');
	}
    public function index()
	{
     if(!($this->session->userdata('user_id')) || $this->session->userdata('user_level') < 9) {
        alert("관리자 아이디로 로그인하여 주시기 바랍니다.", MAIN_URL);
     }

	   $this->load->view('header');
	   $this->load->view('admin/nav');
	   $this->load->view('admin/main');
	   $this->load->view('footer');
	}
}
