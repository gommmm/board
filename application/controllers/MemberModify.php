<?php

class MemberModify extends MY_Controller {
    public function __construct() {
	    parent::__construct();
		$this->load->helper('alert');
             $this->load->helper('captcha');
		$this->load->model('member_model');
	}

	public function index() {
	   $captcha_word = rand(111111,999999);
	   $vals = [
		          'word'          => $captcha_word,
		          'img_path'	=> MAIN_DIRECTORY.'/upload/img/captcha/',
		          'img_url'	=> MAIN_URL.'/upload/img/captcha/',
              'font_path' => MAIN_DIRECTORY.'/resource/fonts/captcha.ttf',
		          'img_width'	=> '357',
		          'img_height' => 50,
              'font_size' => 40,
		          'expiration' => 7200
             ];
	   $cap = create_captcha($vals);

	   if($this->session->userdata('user_id') == '') {
	       alert('로그인해주세요.');
	   }
	   $member = $this->member_model->get_member_one('member', 'm_id', $this->session->userdata('user_id'));
	   $data = [
	   				  'title' => '회원정보수정',
		          'type' => 'm',
						  'submit' => '수정',
						  'm_name' => $member['m_name'],
						  'captcha' => $cap['image'],
              'captcha_word' => $captcha_word
             ];

	   $this->session->set_userdata('captcha_word', $captcha_word);

	    $this->load->view('header');
	    $this->load->view('memberForm', $data);
	    $this->load->view('footer');
	}

	public function modify() {
	  $captcha_word = $this->session->userdata('captcha_word');

	  if($captcha_word != $this->input->post('captcha_word')) {
	    	alert('자동 가입 방지를 위한 문자를 잘못 입력하셨습니다.');
	  }

	  if($this->session->userdata('user_id') == ''){
            alert("로그인 하셔야 합니다.", MAIN_URL."/auth/login");
        }

        if($this->input->post('m_name') == ''){
            alert('이름을 입력해 주세요.');
        }

        if($this->input->post('m_pass') == ''){
            alert('비밀번호를 입력해 주세요.');
        }

        if($this->input->post('m_pass') != $this->input->post('m_pass2')){
            alert('비밀번호를 확인해 주세요.');
        }
		$data = [
             'm_name' => $this->input->post('m_name'),
		         'm_pass' => password_hash($this->input->post('m_pass'), PASSWORD_BCRYPT),
						];

		$this->member_model->update('member', $data, 'm_id', $this->session->userdata('user_id'));
		$this->session->unset_userdata('captcha_word');
	    alert('회원정보가 수정되었습니다.', MAIN_URL);
	}
}
