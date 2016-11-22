<?php

class Install extends My_Controller {
    public function __construct() {
	      parent::__construct();

        $this->load->helper('url');
      	$this->load->helper('alert');
      	$this->load->helper('initialize');

        // 최상위 디렉토리 파일쓰기여부 확인
	      if(!is_writeable(MAIN_DIRECTORY)) {
		        show_error('최상위 디렉토리의 퍼미션을 777 또는 707로 설정하고 새로고침 해주십시오.', 500, $heading = 'Permission Error');
		    }
    }

  public function index() {
	   $this->load->view('header');
	   $this->load->view('install/main');
	   $this->load->view('footer');
	}

	public function step1() {
	   $this->load->view('header');
	   $this->load->view('install/installForm');
	   $this->load->view('footer');
	}

	/*
	   디비를 읽을 수 있게 db 설정파일을 만들어줌
	   변수명 접두사 ad는 admin의 줄임말
	*/
	public function step2() {
	  $host = $this->input->post('host', TRUE);
		$db_id = $this->input->post('db_id', TRUE);
		$db_password = $this->input->post('db_password', TRUE);
		$db_name = $this->input->post('db_name', TRUE);
		$admin_id = $this->input->post('admin_id', TRUE);
		$admin_email = $this->input->post('admin_email', TRUE);
		$admin_name = $this->input->post('admin_name', TRUE);
		$admin_password = password_hash($this->input->post('admin_password', TRUE), PASSWORD_BCRYPT);
		$data = [];

		$xml = createDbConfig($host, $db_id, $db_password, $db_name);

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $dbconfig = loadDbConfig();
        $this->load->database($dbconfig);
    } catch (Exception $e)  {
        unlink(MAIN_DIRECTORY.'/dbconfig.xml');
        show_error("데이터베이스 정보를 다시 입력해주세요.", 500, 'Database Error');
    }

    $this->load->model('initial_model');

		$data['sql'] = file_get_contents(MAIN_DIRECTORY.'/table.sql');
		$data['admin'] = [
								      'm_id' => $admin_id,
		                  'm_name' => $admin_name,
		                  'email' => $admin_email,
								      'm_pass' => $admin_password,
								      'm_level' => 9
								     ];

		$initial_check = $this->initial_model->create($data);

		if($initial_check == FALSE) {
        unlink(MAIN_DIRECTORY.'/dbconfig.xml');
		    show_error('테이블 생성 또는 관리자를 등록하지 못했습니다.', 500, 'Query Error');
		}

		$this->load->view('header');
	  $this->load->view('install/complete');
		$this->load->view('footer');
	}

	/*
	private function createEnv($config) {
        $config['dbdriver'] = "mysql";
        $config['dbprefix'] = "";
        $config['db_debug'] = FALSE;
        $config['pconnect'] = FALSE;
        $config['autoinit'] = FALSE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = "";
        $config['char_set'] = "utf8";
        $config['dbcollat'] = "utf8_general_ci";
        $result = @$this->load->database($config, TRUE);

        if ($result->conn_id === false) {
          alert('DB 정보를 제대로 입력해주세요.');
        }

        $env_file = MAIN_DIRECTORY.'/.env.example';
        $db_config_exam = file_get_contents($env_file);
        $db_config = str_replace(array(
                                       '{hostname}',
									                     '{username}',
									                     '{password}',
									                     '{database}'
									                    ), array(
									                       $config['host'],
									                       $config['db_id'],
									                       $config['db_pass'],
									                       $config['db']
									                       ), $db_config_exam);
        $db_config_file = file_put_contents(MAIN_DIRECTORY.'/.env', $db_config);
		    @chmod($db_config_file, 0606);

		    return $db_config_file;
    }
    */

}
