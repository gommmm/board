<?php

class Register extends MY_Controller {
    public function __construct() {
	    parent::__construct();
	    	$this->load->model('member_model');
		$this->load->helper('alert');
		$this->load->helper('captcha');
	}

	public function index() {
	   $this->load->view('header');
	    $this->load->view('register');
	    $this->load->view('footer');
	}

	public function form() {
		$captcha_word = rand(111111,999999);
		$vals = [
			       'word' => $captcha_word,
			       'img_path'	=> MAIN_DIRECTORY.'/upload/img/captcha/',
			       'img_url'	=> MAIN_URL.'/upload/img/captcha/',
             'font_path' => MAIN_DIRECTORY.'/resource/fonts/captcha.ttf',
			       'img_width'	=> 357,
			       'img_height' => 50,
             'font_size' => 40,
			       'expiration' => 7200,
		        ];

		$cap = create_captcha($vals);

		$data = [
						 'title' => '회원가입',
		         'type' => 'w',
						 'submit' => '가입',
						 'captcha' => $cap['image'],
             'captcha_word' => $captcha_word
            ];

		$this->session->set_userdata('captcha_word', $captcha_word);

		$this->load->view('header');
		$this->load->view('memberForm', $data);
		$this->load->view('footer');
	}

	public function add() {
	    $captcha_word = $this->session->userdata('captcha_word');
	    $id = $this->input->post('m_id');
	    $nick = $this->input->post('m_name');

	    if($captcha_word != $this->input->post('captcha_word')) {
	    	alert('자동 가입 방지를 위한 문자를 잘못 입력하셨습니다.', MAIN_URL.'/register/form');
	    }

	  if($id == '') {
		    alert('아이디를 입력해주세요.');
		}
		if($nick == '') {
		    alert('이름을 입력해주세요.');
		}
		if($this->input->post('m_pass') == '') {
		    alert('비밀번호를 입력해주세요.');
		}
		if($this->input->post('m_pass') != $this->input->post('m_pass2')) {
		    alert('비밀번호를 확인해주세요.');
		}

		$checkMember = $this->member_model->checkMember($id, $nick);
		//$member = $this->member_model->get_member_one('member', 'm_id', $this->input->post('m_id'));

		if($checkMember > 0) {
			alert('아이디나 별명이 중복되었습니다.');
		}

		$data = [
						 'm_id' => $this->input->post('m_id'),
		         'm_name' => $this->input->post('m_name'),
		         'email' => $this->input->post('email'),
						 'm_pass' => password_hash($this->input->post('m_pass'), PASSWORD_BCRYPT),
						 'm_level' => 1
            ];

		$this->member_model->insert('member', $data);
		$this->session->unset_userdata('captcha_word');
		alert('회원가입이 완료되었습니다', MAIN_URL);
	}
}
