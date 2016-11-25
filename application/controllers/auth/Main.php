<?php

class Main extends MY_Controller {

    public function __construct() {
	     parent::__construct();
	     $this->load->model('member_model');
		   $this->load->library(['session', 'email']);
		   $this->load->helper('alert');
  }

	public function index() {
      $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : MAIN_URL;

      $this->load->view('header');
		  $this->load->view('auth/loginForm', ['prev_url' => $referer]);
		  $this->load->view('footer');
	}

	public function loginCheck(){
      $prev_url = $this->input->post('prev_url');

	    if($this->session->userdata('item') != "") {
		      alert('이미 로그인 중입니다.');
	    }

		  if($this->input->post('user') == "") {
		      alert('아이디를 입력해 주세요.');
		  }

		  if($this->input->post('pass') == "") {
		      alert('비밀번호를 입력해주세요.');
		  }

		  $chk_data = $this->member_model->get_member($this->input->post('user'));

		  if($chk_data['m_idx'] != '') {
		      if(password_verify($this->input->post('pass'), $chk_data['m_pass'])) {
              $login_session = rand(111111, 999999);
			        $data = [
				          'user_idx' => $chk_data['m_idx'],
					        'user_id' => $chk_data['m_id'],
					        'user_name' => $chk_data['m_name'],
					        'user_level' => $chk_data['m_level'],
                  'login_session' => $login_session
              ];
              $set = [
                      'is_login' => 1,
                      'login_session' => $login_session
                     ];

              $this->session->set_userdata($data);
              $this->member_model->update('member', $set, 'm_id', $chk_data['m_id']);

				      alert('로그인 되었습니다.', $prev_url);
			    } else {
			        alert('비밀번호가 다릅니다.');
			    }
		  } else {
		      alert('존재하지 않는 회원입니다.');
		  }
	}

	public function logout() {
      if(!$this->session->userdata('user_id'))
          alert('로그인 상태가 아닙니다.', $this->referer);

      $reason = $this->input->get('reason');
      $session_data = ['user_idx', 'user_id', 'user_name', 'user_level', 'login_session'];

      $set = ['is_login' => 0];
      if($reason != 'multiLogin')
          $set['login_session'] = '';

      $this->member_model->update('member', $set, 'm_id', $this->session->userdata['user_id']);
      $this->session->unset_userdata($session_data);

      if($reason == 'timeout') alert('30분간 작업이 없어서 자동 로그아웃 합니다.', MAIN_URL);
      elseif($reason == 'multiLogin') alert('다른곳에서 로그인을 해서 로그아웃 합니다.', MAIN_URL);
      else alert('로그아웃 하셨습니다', MAIN_URL);

  }

	public function find($email, $mode, $title, $userId=NULL) {
		if($email != NULL) {
			$where = [];
			$where['email'] = $email;

			if($userId != NULL) {
				$where['m_id'] = $userId;
			}

			$member = $this->member_model->isMember($where);

			$authNo = rand(111111, 999999);

			$config = [];

      // host, port, user, pass는 사용하는 메일에 맞게 지정하면 된다.
      /*
      $config['mailtype'] = 'text';
			$config['charset'] = 'utf-8';
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = 'ssl://smtp.gmail.com';
			$config['smtp_port'] = 465;
			$config['smtp_user'] = '';
			$config['smtp_pass'] = '';
			$config['smtp_timeout'] = 10;
			$config['crlf'] = "\r\n";
			$config['newline'] = "\r\n";
      */

			// smtp 사용을 원하지 않으면 윗부분을 주석 처리하고 이 부분을 주석해제하면 된다.
			$config['mailtype'] = "text";
			$config['charset'] = "utf-8";
      $config['wordwrap'] = TRUE;

			$this->email->initialize($config);
			$this->email->from('admin@admin.com', '관리자');
			$this->email->to($email);
			$this->email->subject('인증번호입니다.');
			$this->email->message($authNo);

			if($member != NULL && $this->email->send()) {
				$this->session->set_userdata('authNo', $authNo);
				echo 'success';
			} else {
				echo 'fail';
			}

		} else {
			$data['mode'] = $mode;
			$data['title'] = $title;

			if($userId != NULL) {
				$data['userId'] = $userId;
			}

			$this->load->view('header');
			$this->load->view('auth/find', $data);
			$this->load->view('footer');
		}
	}

	public function findId() {
		$email = $this->input->post('email');
		$mode = 'viewId';
		$title = '아이디 찾기';

		$this->find($email, $mode, $title);
	}

	public function viewId() {
		$email = $this->input->post('email');

    if($email == null) {
      alert('올바른 접근경로로 접근해주세요.');
    }

		$data['member'] = $this->member_model->get_member_one('member', 'email', $email);

		$this->load->view('header');
		$this->load->view('auth/viewId', $data);
		$this->load->view('footer');
	}

	public function findPass() {
		$userId = $this->input->post('userId');
		$email = $this->input->post('email');
		$mode = 'changePass';
		$title = '비밀번호 찾기';

		if($userId != NULL) {
			$member = $this->member_model->get_member_one('member', 'm_id', $userId);

			if($member == NULL) {
				alert('입력하신 아이디를 찾을 수 없습니다.');
			}

			$this->find($email, $mode, $title, $userId);

		} else {
			$this->load->view('header');
			$this->load->view('auth/findPass');
			$this->load->view('footer');
		}
	}

	public function changePass() {
		$userId = $this->input->post('userId');
		$email = $this->input->post('email');
		$pass = $this->input->post('pass');

		if($pass != NULL) {
      $pass = password_hash($pass, PASSWORD_BCRYPT);
			$result = $this->member_model->changePass($userId, $pass);

			if($result) {
				alert('비밀번호가 변경되었습니다.', MAIN_URL);
			} else {
				alert('아이디가 존재하지 않습니다.', MAIN_URL);
			}
		} else {

      if($email == null) {
        alert('올바른 접근경로로 접근해주세요');
      }

			$data['userId'] = $userId;

			$this->load->view('header');
			$this->load->view('auth/passForm', $data);
			$this->load->view('footer');
		}
	}

	public function authNoCheck() {
		$authNo = $this->input->post('authNo');

		if($this->session->userdata('authNo') != $authNo) {
			echo "different";
		} else {
			echo 'same';
		}
	}

	public function check() {
    $data = [];
		if($this->input->post('m_id') != NULL) {
			$data['m_id'] = $this->input->post('m_id');
		}
		if($this->input->post('email') != NULL) {
			$data['email'] = $this->input->post('email');
		}
		if($this->input->post('m_name') != NULL) {
			$data['m_name'] = $this->input->post('m_name');
		}

    $type = $this->input->post('type');

    if(count($data) > 0) {
		    $member = $this->member_model->ismember($data);
    }

		if($member != NULL) {
      if($type != 'register' && $member['m_name'] == $this->session->userdata('user_name')) {
        echo 'false';
      } else {
        echo 'true';
      }
		} else {
			echo 'false';
		}
	}
}
