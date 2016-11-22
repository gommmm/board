<?php

class Main extends MY_Controller {
    public function __construct() {
	    parent::__construct();

      $this->load->helper('basic');
  		$this->load->model('board_model');
      $this->load->model('member_model');
	  }

    public function index()
  	{
  	   $posts = $this->board_model->getLatestList('board'); // 최근 등록한 게시물 리스트 변수

  	   $data['menu_list'] = $this->menu_list;
  	   $data['list'] = $posts;
  	   $data['board_name'] = $this->board_name; // html에서 출력해주기 위한 게시판 제목
       $data['content'] = $this->content;

  	   $this->load->view('header');
  	   $this->load->view('main', $data);
  	   $this->load->view('footer');


  	}
}
